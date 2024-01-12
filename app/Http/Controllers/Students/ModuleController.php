<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Progress;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Module;

use App\Http\Requests\ModuleRequest;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function check(Request $request) {
    if(!$request->id) return response([
        'error' => 'Illegal Access',
    ], 500); 
    
    $module = Module::find($request->id);
    return response()->json([
        'module' => $module,
    ]);
  }

  public function index(Request $request) {
    if (!$request->id) return response(['error' => 'Illegal Access'], 500);
    $user = auth('sanctum')->user();
    $student = Student::where([
      "user_id" => $user->id,
    ])->first();

    $subject = Subject::find($request->id);
    $progress = Progress::firstOrCreate([
      'student_id' => $student->id,
      'subject_id' => $subject->id,
    ]);
    
    $step = $progress->progress + 1;
    $modules = Module::where([
      "subject_id" => $subject->id,
      "active" => 1,
    ])
    ->with('questions')
    ->where('step', '<=', $step)
    ->has('questions')
    ->with('subject')
    ->get();
    
    return response()->json([
      'progress' => $progress,
      'modules' => $modules
    ]);
  }
}
