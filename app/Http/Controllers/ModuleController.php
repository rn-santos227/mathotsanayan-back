<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Subject;
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

    public function check(Request $request) {
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);
        
        $module = Module::find($request->id);
        return response()->json([
            'module' => $module,
        ]);
    }
    

    public function student(Request $request) {
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);

        $subject = Subject::find($request->id);
        $progress = Progress::firstOrCreate([
            'student_id' => $request->query('student_id'),
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
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);

        $request->validated();
        $module = Module::find($request->id);

        if (!$module) return response(['error' => 'Illegal Access'], 500);
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

    public function delete(Request $request){
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);
        $module = Module::find($request->id);

        if (!$module) return response(['error' => 'Illegal Access'], 500);
        if ($module->active) return response(['error' => 'Cannot delate when its active.'], 500);

        $module->delete();
        return response([
            'module' => $module,
        ], 201); 
    }
}
