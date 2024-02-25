<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Module;
use App\Models\Progress;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;

class DashboardController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $student = Student::where('user_id', $user->id)->first();
    
    $progress = Progress::where([
      'student_id' => $student->id,
    ])->first();

    $step = $progress->progress + 1;
    $modules = Module::where([
      "active" => 1,
    ])
    ->where('step', '<=', $step)
    ->has('questions')
    ->count();

    $subjects = Subject::has('modules')->count();

    $results = Result::with('student')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
        'student_id' => $student->id,
    ])->count();

    return response()->json([
      'dashboard' => [
        'modules' => $modules,
        'subjects' => $subjects,
        'results' => $results,
      ]]);
  }

  public function ratio() {
    $user = auth('sanctum')->user();
    $student = Student::where('user_id', $user->id)->first();
    
    $graph = [
      'passed' => 0,
      'failed' => 0,
    ];

    $results = Result::where([
      'completed' => 1,
      'invalidate' => 0,
      'student_id' => $student->id,
    ])->get();

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
    $user = auth('sanctum')->user();
    $student = Student::where('user_id', $user->id)->first();
    $modules = Module::where([
      "active" => 1,
    ])
    ->has('questions')
    ->get();
    $result_modules = [];

    foreach($modules as $module) {
      $result_module = [
        'module' => $module,
        'passed' => 0,
        'failed' => 0,
        'total' => 0,
      ];

      $results = Result::where([
        'completed' => 1,
        'invalidate' => 0,
        'module_id' => $module->id,
        'student_id' => $student->id,
      ])->get();

      foreach ($results as $result) {
        $module = $result->module;
        $result_module['total'] =  $result_module['total'] + 1;
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
