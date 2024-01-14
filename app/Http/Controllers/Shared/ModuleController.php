<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use App\Models\Module;
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
}
