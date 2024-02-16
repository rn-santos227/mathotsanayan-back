<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Correct;
use App\Models\Question;

class TestController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function submit(Request $request) {
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

    return response([
      'solution' => $solution,
      'correct' => $check,
    ], 201);
  }
}
