<?php

namespace App\Http\Controllers;

use App\Models\Correct;
use App\Models\Question;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function submit(Request $request) {
        if($request->id) {
            $question = Question::find($request->id);
            $question->load('corrects');
            $check = false;
            $solution = Correct::where([
                "question_id" => $question->id,
            ])->first();
            

            foreach($question->corrects as $correct) {
                $trim_answer = trim($request->content);
                $trim_correct = trim($correct->content);
                if(strtolower($trim_answer) == strtolower($trim_correct)) {
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
