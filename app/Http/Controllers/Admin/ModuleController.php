<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Module;

use App\Http\Requests\ModuleRequest;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $modules = Module::with('subject', 'questions', 'questions.corrects', 'questions.options')
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

  public function create(ModuleRequest $request) {
    $request->validated();
    $module = Module::create([
      'name' => $request->name,
      'objective' => $request->objective,
      'description' => $request->description,
      'direction' => $request->direction,
      'passing' => $request->passing,
      'step' => $request->step,
      'subject_id' => $request->subject,
    ])->load('subject', 'questions', 'questions.solutions', 'questions.options');

    return response([
        'module' => $module,
    ], 201);
  }

  public function update(ModuleRequest $request) {
    $request->validated();
    $module = Module::find($request->id);
    $module->update([
      'name' => $request->name,
      'objective' => $request->objective,
      'description' => $request->description,
      'direction' => $request->direction,
      'passing' => $request->passing,
      'step' => $request->step,
      'active' => $request->active,
      'subject_id' => is_numeric($request->subject) ? $request->subject['id'] : $request->subject_id,
    ]);
    $module->load('subject', 'questions', 'questions.corrects', 'questions.options');
    return response([
        'module' => $module,
    ], 201);
  }

  public function delete(ModuleRequest $request){
    $module = Module::find($request->id);
    if ($module->active) return response(['error' => 'Cannot delate when its active.'], 500);
    if ($module->questions()->count() > 0) {
      return response([
          'message' => 'Cannot module teacher with questions.',
      ], 400);
    }

    $module->delete();
    return response([
        'module' => $module,
    ], 201); 
  }
}
