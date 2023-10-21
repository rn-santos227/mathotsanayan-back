<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Models\Solution;
use App\Models\Correct;
use App\Models\Option;
use App\Models\Question;

use App\Http\Requests\QuestionRequest;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function createMany(Request $request) {
        $questions = array();
        foreach($request->questions as $question) {
            $new_question = Question::create([
                'content' => $question->content,
                'type' => $question->type,
                'module_id' => $request->module,
                'subject_id' => $request->subject,
            ]);

            $file_name = "question-".$new_question->id."module".$request->module.".png";

            if (!Storage::exists('questions/'.$file_name)) {
                Storage::disk('minio')->put('questions/'.$file_name, (string) $question->file);
            }

            foreach($question['options'] as $option) {
                Option::create([
                    'content' => $option['content'],
                    'module_id' => $request->module,
                    'subject_id' => $request->subject,
                    'question_id' => $new_question->id,
                ]);
            };

            foreach($question['options'] as $option) {
                Correct::create([
                    'content' => $option['content'],
                    'module_id' => $request->module,
                    'subject_id' => $request->subject,
                    'question_id' => $new_question->id,
                ]);
            };

            foreach($question['options'] as $option) {
                Solution::create([
                    'solution' => $option['content'],
                    'module_id' => $request->module,
                    'subject_id' => $request->subject,
                    'question_id' => $new_question->id,
                ]);
            };

            $new_question->load('corrects', 'options', 'solutions');
            array_push($questions, $new_question);
        }

        return response([
            'questions' => $questions,
        ], 201);
    }

    public function create(QuestionRequest $request) {
        $request->validated();
        $question = Question::create([
            'content' => $request->content,
            'type' => $request->type,
            'module_id' => $request->module,
            'subject_id' => $request->subject,
        ]);

        return response([
            'question' => $question,
        ], 201);
    }

    public function update(QuestionRequest $request) {
        $request->validated();
        if($request->id) {
            $question = Question::find($request->id);
            $question->update([
                'content' => $request->content,
                'type' => $request->type,
                'module_id' => $request->module,
                'subject_id' => $request->subject,
            ]);
            return response([
                'question' => $question,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500);
    }

    public function delete(Request $request){
        if($request->id) {
            $question = Question::find($request->id);
            $question->delete();
            return response([
                'question' => $question,
            ], 201);
        } 
        else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
