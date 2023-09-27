<?php

namespace App\Http\Controllers;

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

    public function delete(Request $request ){
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
