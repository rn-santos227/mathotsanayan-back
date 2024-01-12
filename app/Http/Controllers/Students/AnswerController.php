<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Answer;
use App\Models\Student;

use App\Http\Requests\AnswerRequest;

class AnswerController extends Controller
{
  public function index(AnswerRequest $request) {
    $user = auth('sanctum')->user();
    $student = Student::where([
      "user_id" => $user->id,
    ])->first();

    $answers = Answer::with('grade','question')
    ->where([
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
