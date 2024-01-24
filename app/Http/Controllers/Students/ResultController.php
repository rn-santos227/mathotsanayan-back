<?php

namespace App\Http\Controllers\Students;

use App\Models\Result;
use App\Models\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResultRequest;

class ResultController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index(ResultRequest $request) {
    $user = auth('sanctum')->user();
    $student = Student::where([
      "user_id" => $user->id,
    ])->first();

    $results = Result::with('module', 'progress')
    ->where([
        'student_id' => $student->id,
        'completed' => 1,
        'invalidate' => 0,
    ])
    ->whereHas('module', function ($query) {
        $query->where('active', 1);
    })
    ->get();
    $results->makeVisible(['timer', 'completed', 'total_score']);
    return response([
        'results' => $results
    ], 200);
  }
}
