<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Module;

class ModuleController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $modules = Module::with('subject', 'questions', 'questions.corrects', 'questions.options')
    ->where([
      "active" => 1,
    ])
    ->orderBy('created_at', 'desc')->paginate(10);
    
    return response()->json([
      'modules' => $modules
    ]);
  }
}
