<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;

use App\Models\Result;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;

class DashboardController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $schools = School::count();
    $sections = Section::where([
      'teacher_id' => $teacher->id,
    ])
    ->count();

    $students = Student::with('section')
    ->whereHas('section', function ($query) use($teacher) {
      $query->where([
        'teacher_id' => $teacher->id,
      ]);
    })
    ->count();

    $results = Result::with('student')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
    ])->whereHas('student', function ($query) use($teacher) {
      $query->whereHas('section', function ($query) use ($teacher) {
        $query->where([
          'teacher_id' => $teacher->id,
        ]);
      });
    })
    ->count();

    return response()->json([
    'dashboard' => [
      'schools' => $schools,
      'sections' => $sections,
      'students' => $students,
      'results' => $results,
    ]]);
  }
}