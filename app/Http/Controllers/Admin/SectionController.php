<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Section;

use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum, admin');
  }

  public function index() {
    $sections = Section::with('teacher', 'school', 'students')
    ->orderBy('created_at', 'desc')
    ->get();
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
    $request->validated();
    $section = Section::find($request->id);
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

  public function delete(SectionRequest $request ){
    $section = Section::find($request->id);

    if ($section->students()->count() > 0) {
      return response([
          'message' => 'Cannot delete section with students.',
      ], 400);
    }

    $section->delete();
    return response([
      'section' => $section,
    ], 201);
  }
}
