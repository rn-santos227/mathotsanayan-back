<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Module;
use App\Models\Result;
use App\Models\School;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;

class DashboardController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
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
      'modules' => $modules,
      'results' => $results,
      'schools' => $schools,
      'students' => $students,
      'subjects' => $subjects,
      'teachers' => $teachers
    ]]);
  }

  public function ratio() {
    $graph = [
      'passed' => 0,
      'failed' => 0,
    ];

    $results = Result::where([
      'completed' => 1,
      'invalidate' => 0,
    ])->whereHas('student', function ($query) {
      $query->whereNull('students.deleted_at');
    })->get();
    $results->makeVisible(['total_score', 'grade', 'module']);

    foreach ($results as $result) {
      $module = $result->module;
      if($result->grade >= $module->passing) {
        $graph['passed'] =  $graph['passed'] + 1;
      } else {
        $graph['failed'] =  $graph['failed'] + 1;
      }
    }

    return response()->json([
      'graph' => $graph
    ]);
  }
}
