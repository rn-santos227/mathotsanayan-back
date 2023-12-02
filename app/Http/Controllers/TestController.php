<?php

namespace App\Http\Controllers;

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
            $correct = 0;

            foreach($question->corrects as $correct) {
                if(strtolower($correct->content) == strtolower($request->content)) {
                    $correct = 1;
                }
            }

            return response([
                'correct' => $correct,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500);
    }
}
