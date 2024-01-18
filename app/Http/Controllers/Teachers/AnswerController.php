<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Answer;
use App\Models\Result;
use App\Models\Teacher;

use App\Http\Requests\AnswerRequest;

class AnswerController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index(AnswerRequest $request) {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $result = Result::where([ 
      "id" => $request->id,
    ])->whereHas('student', function ($query) use ($teacher) {
      $query->whereNull('students.deleted_at')
      ->whereHas('section', function($query) use ($teacher) {
        $query->where('teacher_id', $teacher->id);
      });
    })->first();

    if(!$result) return response(['error' => 'Illegal Access'], 500);

    $answers = Answer::with('grade','question')
    ->where([
      'result_id' => $result->id,
    ])
    ->orderBy('created_at', 'desc')
    ->get();

    return response([
      'answers' => $answers
    ], 200);
  }
}
