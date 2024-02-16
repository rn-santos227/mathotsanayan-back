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
  
  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $modules = Module::with('subject', 'questions', 'questions.corrects', 'questions.options')
    ->where(function($query) use($request) {
      $category = $request->query('category');
      $search = $request->query('search');
      switch ($category) {
        case 'name':
          $query->where('name', 'like', '%' . $search . '%');
          break;
  
        case 'subject.name':
          $query->whereHas('subject', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
          });
          break;
    
        case 'description':
          $query->where('name', 'like', '%' . $search . '%');
          break;
      }
    })
    ->orderBy('created_at', 'desc')
    ->get();

    return response()->json([
      'modules' => $modules,
    ]);
  }
}
