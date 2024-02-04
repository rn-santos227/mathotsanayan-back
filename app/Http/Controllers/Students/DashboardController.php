<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Module;
use App\Models\Progress;
use App\Models\Result;
use App\Models\Student;

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
    ->with('subject')
    ->count();

    $results = Result::with('student')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
        'student_id' => $student->id,
    ])->count();

    return response()->json([
      'dashboard' => [
        'modules' => $modules,
        'results' => $results,
      ]]);
  }

  public function ratio() {
    $user = auth('sanctum')->user();
    $student = Student::where('user_id', $user->id)->first();
    
    $results = Result::with('module', 'progress')
    ->where('student_id', $student->id)
    ->where('completed', 1)
    ->where('invalidate', 0)
    ->whereHas('module', function ($query) {
      $query->where('active', 1);
    })
    ->get()
    ->makeVisible(['timer', 'completed', 'total_score']);
    
    $passed = $failed = 0;
    
    foreach ($results as $result) {
      $grade = ($result->total_score / $result->items) * 100;
      ($grade >= $result->module->passing) ? $passed++ : $failed++;
    }
    
    return response()->json([
      'dashboard' => [
        'passed' => $passed,
        'failed' => $failed,
      ],
    ], 200);
  }
}
