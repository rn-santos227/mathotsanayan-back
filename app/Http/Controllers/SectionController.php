<?php

namespace App\Http\Controllers;

use App\Models\Section;

use App\Http\Requests\SectionRequest;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum, admin');
    }

    public function index() {
        $sections = Section::with('teacher', 'school', 'students')->get();
        return response()->json([
            'sections' => $sections
        ]);
    }

    public function create(SectionRequest $request) {
        $request->validated();
        $section = Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher,
            'school_id' => $request->school,
        ])->load('teacher', 'school', 'students');

        return response([
            'section' => $section,
        ], 201);

    }

    public function update(SectionRequest $request) {
        if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

        $request->validated();
        $section = Section::find($request->id);
        if(!$section) return response(['error' => 'Illegal Access',], 500); 
        $section->update([
            'name' => $request->name,
            'description' => $request->description,
            'teacher_id' => $request->teacher,
            'school_id' => $request->school,
        ]);

        $section->load('teacher', 'school', 'students');
        return response([
            'section' => $section,
        ], 201);
    }

    public function delete(Request $request ){
        if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

        $section = Section::find($request->id);
        if(!$section) return response(['error' => 'Illegal Access',], 500); 
        $section->delete();
        return response([
            'section' => $section,
        ], 201);
    }
}
