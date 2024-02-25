<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;

use App\Models\Module;
use App\Models\Result;
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

    $modules = Module::where([
      "active" => 1,
    ])
    ->has('questions')
    ->count();

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
      'sections' => $sections,
      'students' => $students,
      'modules' => $modules,
      'results' => $results,
    ]]);
  }

  public function ratio() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $graph = [
      'passed' => 0,
      'failed' => 0,
    ];

    $results = Result::where([
      'completed' => 1,
      'invalidate' => 0,
    ])->whereHas('student', function ($query) use($teacher) {
      $query->whereNull('students.deleted_at')
      ->whereHas('section', function ($query) use($teacher) {
        $query->where([
          'teacher_id' => $teacher->id,
        ]);
      });
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
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

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
      ])->whereHas('student', function ($query) use($teacher) {
        $query->whereNull('students.deleted_at')
        ->whereHas('section', function ($query) use($teacher) {
          $query->where([
            'teacher_id' => $teacher->id,
          ]);
        });
      })->get();

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
