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

    public function create(QuestionRequest $request) {
        $request->validated();
        $questions = array();
        foreach($request->questions as $question) {
            $new_question = Question::create([
                'content' => $question->content,
                'module_id' => $request->module,
                'subject_id' => $request->subject,
            ]);
            array_push($questions, $new_question);
        }

        return response([
            'questions' => $questions,
        ], 201);
    }

}
