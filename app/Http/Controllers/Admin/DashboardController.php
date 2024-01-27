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

  public function modules() {
    $modules = Module::get();
    $result_modules = [];

    foreach($modules as $module) {
      $result_module = [
        'modules' => $module,
        'passed' => 0,
        'failed' => 0,
      ];

      $results = Result::where([
        'completed' => 1,
        'invalidate' => 0,
        'module_id' => $module->id,
      ])->get();

      foreach ($results as $result) {
        $module = $result->module;
        if($result->grade >= $module->passing) {
          $result_module['passed'] =  $result_module['passed'] + 1;
        } else {
          $result_module['failed'] =  $result_module['failed'] + 1;
        }
      }
      array_push($result_modules, $result_module);
    }

    return response()->json([
      'result_modules' => $result_modules
    ]);
  }
}
