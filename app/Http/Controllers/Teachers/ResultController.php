<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Result;
use App\Models\Teacher;

use App\Http\Requests\ResultRequest;
use Illuminate\Http\Request;

class ResultController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $results = Result::with('module', 'progress', 'student', 'student.section', 'student.school')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
    ])
    ->whereHas('student', function ($query) use ($teacher) {
        $query->whereNull('students.deleted_at')
        ->whereHas('section', function($query) use ($teacher) {
          $query->where('teacher_id', $teacher->id);
        });
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    $results->each(function ($result) {
      $result->makeVisible(['timer', 'completed', 'total_score']);
    });
    return response([
        'results' => $results
    ], 200);
  }
}
