<?php

namespace App\Http\Controllers\Teachers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $students = Student::with('section','school','course')
    ->where(function($query) use($teacher) {
      $query->whereHas('section', function ($query) use ($teacher) {
        $query->where([
          'teacher_id' => $teacher->id
        ]);
      });
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    return response()->json([
      'students' => $students
    ]);
  }
}
