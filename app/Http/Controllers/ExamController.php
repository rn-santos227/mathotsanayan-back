<?php

namespace App\Http\Controllers;

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
                'progress_id' => $progress->id,
                'module_id' => $module->id,
                'student_id' => $request->query('student_id'),
            ]);
        }

        $result->update([
            'total_score' => 0,
            'timer' => null,
        ]);

        $questions = Question::with('options')->where([
            'module_id' => $request->id,
        ])->get()->shuffle();

        return response([
            'result' => $result,
            'questions' => $questions,
        ], 201);
    }

    public function answer(Request $request) {
        if (!$request->id) {
            return response(['error' => 'Illegal Access'], 500);
        }
    
        $result = Result::find($request->result);
        $progress = Progress::find($result->progress_id);
        $question = Question::find($request->id);
        $question->load('corrects');
        
        $check = false;
        $solution = Correct::where('question_id', $question->id)->first();
        
        $answer = Answer::updateOrCreate([
            'progress_id' => $progress->id,
            'student_id' => $result->student_id,
            'result_id' => $result->id,
            'question_id' => $request->id,
        ],[
            'content' => $request->content,
            'timer' => $request->timer,
            'attempts' => $request->attempts,
        ]);
    
        foreach ($question->corrects as $correct) {
            if (strtolower($correct->content) == strtolower($request->content)) {
                $check = true;
                $solution = Correct::find($correct->id);
                break;
            }
        }
    
        if ($check) {
            $result->update(['total_score' => $result->total_score + 1]);
        }

        Grade::updateOrCreate([
            'progress_id' => $progress->id,
            'student_id' => $result->student_id,
            'result_id' => $result->id,
            'question_id' => $request->id,
            'answer_id' => $answer->id,
            'correct_id' => $solution->id,
        ], [
            'evaluation' => $check ? 'correct' : 'wrong',
            'skipped' => 0,
        ]);
    
        return response([
            'solution' => $check ? $solution : '',
            'correct' => $check,
        ], 201);
    }

    public function submit(Request $request) {
        if (!$request->id) {
            return response(['error' => 'Illegal Access'], 500);
        }

        $result = Result::find($request->id);

        $module = Module::find($result->module_id);
        $module->load("questions");

        $progress = Progress::find($result->progress_id);

        $result->makeVisible([
            'timer',
            'completed',
            'total_score',
        ]);
        $result->update([
            'completed' => 1,
        ]);

        $question_count = $module->questions->count();
        $total_score = $result->total_score;
        $passing = $module->passing;

        $grade = ($total_score / $question_count) * 100;
        if($grade >= $passing) {

        }
    }
}
