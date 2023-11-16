<?php

namespace App\Http\Controllers;

use App\Models\Module;

use App\Http\Requests\ModuleRequest;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $modules = Module::with('subject', 'questions', 'questions.solutions', 'questions.options')->get();
        return response()->json([
            'modules' => $modules
        ]);
    }

    public function create(ModuleRequest $request) {
        $request->validated();
        $module = Module::create([
            'name' => $request->name,
            'objective' => $request->objective,
            'description' => $request->description,
            'direction' => $request->objective,
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
        if($request->id) {
            $module = Module::find($request->id);
            $module->update([
                'name' => $request->name,
                'objective' => $request->objective,
                'description' => $request->description,
                'direction' => $request->objective,
                'passing' => $request->passing,
                'step' => $request->step,
                'subject_id' => $request->subject,
            ])->load('subject', 'questions', 'questions.solutions', 'questions.options');
            return response([
                'module' => $module,
            ], 201);
        }  else return response([
            'error' => 'Illegal Access',
        ], 500);
    }

    public function delete(Request $request){
        if($request->id) {
            $module = Module::find($request->id);
            $module->delete();
            return response([
                'module' => $module,
            ], 201);
        } 
        else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
