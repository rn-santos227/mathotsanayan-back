<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Module;
use App\Models\Question;

class QuestionController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index(Request $request) {
    if(!$request->id) return response([
      'error' => 'Illegal Access',
    ], 500); 
    $module = Module::find($request->id);
    if (!$module) return response(['error' => 'Illegal Access'], 500);

    $questions = Question::with('corrects','options')->where([
      'module_id' => $module->id
    ])->get();

    return response([
      'questions' => $questions,
    ], 201);
  }
}
