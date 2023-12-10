<?php

namespace App\Http\Controllers;

use App\Models\Correct;
use App\Models\Result;
use App\Models\Question;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function question(Request $request) {
        if($request->id) {
            $question = Question::where([
                "module_id" => $request->id,
            ])->inRandomOrder()->first();

            return response([
                'question' => $question,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function answer(Request $request) {
        if($request->id) {
            $question = Question::find($request->id);
            $question->load('corrects');
            $check = false;
            $solution = Correct::where([
                "question_id" => $question->id,
            ])->first();
            

            foreach($question->corrects as $correct) {
                if(strtolower($correct->content) == strtolower($request->content)) {
                    $check = true;
                    $solution = Correct::find($correct->id);
                    break;
                }
            }

            return response([
                'solution' => $solution,
                'correct' => $check,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
