<?php

namespace App\Http\Controllers;

use App\Models\Answer;

use App\Http\Requests\AnswerRequest;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index(AnswerRequest $request) {
    $answers = Answer::where([
      'result_id' => $request->id,
    ])
    ->orderBy('created_at', 'desc')
    ->get();

    return response([
      'answers' => $answers
    ], 200);
  }
}
