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
        $modules = Module::with('subject', 'questions', 'questions.corrects', 'questions.options')->get();
        return response()->json([
            'modules' => $modules
        ]);
    }

    public function student() {
        $modules = Module::with('subject', 'questions', 'questions.options')->get();
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
        if($request->id) {
            $request->validated();
            $module = Module::find($request->id);
            $module->update([
                'name' => $request->name,
                'objective' => $request->objective,
                'description' => $request->description,
                'direction' => $request->direction,
                'passing' => $request->passing,
                'step' => $request->step,
                'subject_id' => is_numeric($request->subject) ? $request->subject['id'] : $request->subject_id,
            ]);
            $module->load('subject', 'questions', 'questions.corrects', 'questions.options');
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
