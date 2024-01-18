<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Answer;

use App\Http\Requests\AnswerRequest;

class AnswerController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }
  public function index(AnswerRequest $request) {
    $answers = Answer::with('grade','question')
    ->where([
      'result_id' => $request->id,
    ])
    ->orderBy('created_at', 'desc')
    ->get();

    return response([
      'answers' => $answers
    ], 200);
  }
}
