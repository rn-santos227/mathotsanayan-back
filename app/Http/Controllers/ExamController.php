<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Correct;
use App\Models\Grade;
use App\Models\Progress;
use App\Models\Module;
use App\Models\Question;
use App\Models\Result;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function questions(Request $request) {
        if (!$request->id) {
            return response(['error' => 'Illegal Access'], 500);
        }

        $module = Module::find($request->id);
        if (!$module) {
            return response(['error' => 'Illegal Access'], 500);
        }
        $module->load("questions");

        $progress = Progress::where([
            'student_id' => $request->query('student_id'),
            'subject_id' => $module->subject_id,
        ])->first();

        $result = Result::where([
            'completed' => 0,
            'progress_id' => $progress->id,
            'module_id' => $module->id,
            'student_id' => $request->query('student_id'),
        ])->first();
        
        if (!$result) {
            $result = Result::create([
                'total_score' => 0,
                'items' => $module->questions->count(),
                'progress_id' => $progress->id,
                'module_id' => $module->id,
                'student_id' => $request->query('student_id'),
            ]);
        } else {
            $answers = Answer::where([
                'progress_id' => $progress->id,
                'student_id' => $result->student_id,
                'result_id' => $result->id,
                'module_id' => $result->module_id,
            ])->get();
            $answers->load('grade');

            foreach ($answers as $answer) {
                $answer->grade->forceDelete();
                $answer->forceDelete();
            }

            $result->update([
                'total_score' => 0,
                'items' => $module->questions->count(),
                'timer' => 0,
            ]);
        }

        $questions = Question::with('options')->where([
            'module_id' => $request->id,
        ])->get()->shuffle();

        return response([
            'result' => $result,
            'questions' => $questions,
        ], 201);
    }

    public function skip(Request $request) {
        if (!$request->id) {
            return response(['error' => 'Illegal Access'], 500);
        }
        $result = Result::find($request->result);
        if (!$result) {
            return response(['error' => 'Illegal Access'], 500);
        }

        $progress = Progress::find($result->progress_id);
        $question = Question::find($request->id);
        $question->load('corrects');
        $correct = $question->corrects->first();

        $answer = Answer::create([
            'progress_id' => $progress->id,
            'student_id' => $result->student_id,
            'result_id' => $result->id,
            'module_id' => $result->module_id,
            'question_id' => $request->id,
            'content' => $request->content,
            'timer' => $request->timer,
            'attempts' => $request->attempts,
        ]);

        $result->update([
            'timer' => $result->timer + $answer->timer,
        ]);

        Grade::create([
            'student_id' => $result->student_id,
            'result_id' => $result->id,
            'module_id' => $result->module_id,
            'question_id' => $request->id,
            'correct_id' => $correct->id,
            'answer_id' => $answer->id,
            'evaluation' => $check ? 'correct' : 'wrong',
            'skipped' => 1,
        ]);
    }

    public function answer(Request $request) {
        if (!$request->id) {
            return response(['error' => 'Illegal Access'], 500);
        }
    
        $result = Result::find($request->result);
        if (!$result) {
            return response(['error' => 'Illegal Access'], 500);
        }

        $progress = Progress::find($result->progress_id);
        $question = Question::find($request->id);
        $question->load('corrects');
        
        $check = false;
        $solution = Correct::where('question_id', $question->id)->first();
        
        $answer = Answer::create([
            'progress_id' => $progress->id,
            'student_id' => $result->student_id,
            'result_id' => $result->id,
            'module_id' => $result->module_id,
            'question_id' => $request->id,
            'content' => $request->content,
            'timer' => $request->timer,
            'attempts' => $request->attempts,
        ]);
    
        foreach($question->corrects as $correct) {
            $trim_answer = trim($request->content);
            $trim_correct = trim($correct->content);
            if(strtolower($trim_answer) == strtolower($trim_correct)) {
                $check = true;
                $solution = Correct::find($correct->id);
                break;
            }
        }
    
        $result->update([
            'total_score' => $check ? $result->total_score + 1 : $result->total_score,
            'timer' => $result->timer + $answer->timer,
        ]);

        Grade::create([
            'student_id' => $result->student_id,
            'result_id' => $result->id,
            'module_id' => $result->module_id,
            'question_id' => $request->id,
            'correct_id' => $solution->id,
            'answer_id' => $answer->id,
            'evaluation' => $check ? 'correct' : 'wrong',
            'skipped' => 0,
        ]);
    
        return response([
            'solution' => $check ? $solution : '',
            'correct' => $check,
        ], 201);
    }

    public function submit(Request $request) {
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);

        $result = Result::find($request->id);
        if (!$result) return response(['error' => 'Illegal Access'], 500);

        $module = Module::find($result->module_id);
        $module->load("questions");

        $progress = Progress::find($result->progress_id);

        $result->makeVisible(['timer', 'completed', 'total_score']);
        $result->update(['completed' => 1]);
        $result->load('answers', 'answers.question', 'answers.grade', 'module', 'progress');
        
        $module = Module::find($result->module_id);
        $module->load('questions');
        
        $question_count = $module->questions->count();
        $total_score = $result->total_score;
        $passing = $module->passing;
        
        $grade = ($total_score / $question_count) * 100;
        $tries = $progress->tries;
        $stage = $progress->progress;

        if($grade >= $passing) {
            $passed = $progress->passed;
            $progress->update([
                'tries' => $tries + 1,
                'passed' => $passed + 1,
                'timer' =>  $progress->timer + $result->timer,
                'progress' => ($stage + 1) <= $module->step ?  ($stage + 1) : $stage,
            ]);
        } else {
            $failed = $progress->failed;
            $progress->update([
                'tries' => $tries + 1,
                'failed' => $failed + 1,
                'timer' =>  $progress->timer + $result->timer,
            ]);
        }

        return response([
            'result' => $result,
        ], 201);
    }
}
