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
        $modules = Module::with('course', 'questions.solutions', 'questions.options')->get();
        return response()->json([
            'modules' => $modules
        ]);
    }

    public function create(ModuleRequest $request) {
        $request->validated();
        $module = Module::create(
            $request->only([
                'name',
                'description',
                'step',
                'subject_id',
            ])
        );

        return response([
            'module' => $module,
        ], 201);
    }

    public function update(ModuleRequest $request) {
        $request->validated();
        if($request->id) {
            $module = Module::find($request->id);
            $module->update(
                $request->only([
                    'name',
                    'description',
                    'step',
                    'subject_id',
                ])
            );
            return response([
                'module' => $module,
            ], 201);
        }  else return response([
            'error' => 'Illegal Access',
        ], 500);
    }

    public function delete(Request $request ){
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
