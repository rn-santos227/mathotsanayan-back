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
            $solution = [];

            foreach($question->corrects as $correct) {
                $solution = Correct::find($correct->id);
                if(strtolower($correct->content) == strtolower($request->content)) {
                    $check = true;
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
