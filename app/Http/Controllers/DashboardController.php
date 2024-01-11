<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Result;
use App\Models\School;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function admin() {
    $courses = Course::count();
    $modules = Module::count();
    $results = Result::where([
      'completed' => 1,
      'invalidate' => 0,
    ])->whereHas('student', function ($query) {
      $query->whereNull('students.deleted_at');
    })
    ->count();
    $schools = School::count();
    $students = Student::count();
    $subjects = Subject::count();
    $teachers = Teacher::count(); 

    return response()->json([
    'dashboard' => [
      'courses' => $courses,
      'modules' => $modules,
      'results' => $results,
      'schools' => $schools,
      'students' => $students,
      'teachers' => $teachers,
      'subjects' => $subjects
    ]]);
  }

  public function teacher(Request $request) {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $schools = School::count();
  
    $sections = Section::where([
      'teacher_id' => $teacher->id,
    ])
    ->count();

    $students = Subject::with('section')
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

  public function student(Request $request) {
    $user = auth('sanctum')->user();
    $student = Student::where([
      "user_id" => $user->id,
    ])->first();
  }
}
