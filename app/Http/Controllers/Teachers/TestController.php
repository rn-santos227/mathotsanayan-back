<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;

use App\Models\Correct;
use App\Models\Question;

use App\Http\Requests\TestRequest;

class TestController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function submit(TestRequest $request) {
    if (!$request->id) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $question = Question::find($request->id);
    $question->load('corrects');
    $question->testQuestion($question);
    
    $check = false;
    $solution = null;

    foreach ($question->corrects as $correct) {
      $trim_answer = trim($request->content);
      $trim_correct = trim($correct->content);

      if (strtolower($trim_answer) == strtolower($trim_correct)) {
        $check = true;
        $solution = Correct::find($correct->id);
        break;
      }
    }

    if(!$check) {
      $solution = Correct::where([
        'question_id' => $question->id
      ])->inRandomOrder()->first();
    }

    return response([
      'solution' => $solution,
      'correct' => $check,
    ], 201);
  }
}
