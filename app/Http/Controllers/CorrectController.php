<?php

namespace App\Http\Controllers;

use App\Models\Correct;

use App\Http\Requests\CorrectRequest;
use Illuminate\Http\Request;

class CorrectController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function create(CorrectRequest $request) {
        if($request->id) {
            $request->validated();
            
            $question = Question::find($id);
            Correct::create([
                'content' => $request->content,
                'solution' => $$request->solution,
                'module_id' => $question->module,
                'subject_id' => $question->subject,
                'question_id' => $question->id,
            ]);

            $corrects = Correct::where([
                "question_id" => $question->id,
            ])->get();
            return response([
                'corrects' => $corrects,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function update(CorrectRequest $request) {
        if($request->id) {
            $request->validated();

            $corrects = Correct::where([
                "question_id" => $question->id,
            ])->get();
            return response([
                'corrects' => $corrects,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function delete(Request $request ) { 
        if($request->id) {
            $correct = Correct::find($request->id);
            $correct->delete();
            return response([
                'correct' => $correct,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
