<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Student;

use App\Http\Requests\AnswerRequest;

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

  public function student(AnswerRequest $request) {
    $user = auth('sanctum')->user();
    $student = Student::where([
      "user_id" => $user->id,
    ])->first();

    $answers = Answer::where([
      'result_id' => $request->id,
      'student_id' => $student->id,
    ])
    ->orderBy('created_at', 'desc')
    ->get();

    return response([
      'answers' => $answers
    ], 200);
  }
}
