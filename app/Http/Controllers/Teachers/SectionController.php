<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Section;
use App\Models\Teacher;

use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $sections = Section::with('school', 'students')->where([
      'teacher_id' => $teacher->id
    ])->get();

    return response()->json([
      'sections' => $sections
    ]);
  }

  public function create(SectionRequest $request) {
    $request->validated();

    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $section = Section::create([
      'name' => $request->name,
      'description' => $request->description,
      'teacher_id' => $teacher->id,
      'school_id' => $request->school,
    ])->load('teacher', 'school', 'students');

    return response([
      'section' => $section,
    ], 201);
  }

  public function update(SectionRequest $request) {
    $request->validated();

    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      'user_id' => $user->id,
    ])->first();


    $section = Section::where([
      'id' => $request->id,
      'teacher_id' => $teacher->id,
    ])->fist();

    if(!isset($section)) {
      return response([
        'error' => 'Illegal Access',
      ], 201);
    }

    $section->update([
      'name' => $request->name,
      'description' => $request->description,
      'school_id' => is_numeric($request->school) ? $request->school['id'] : $request->school_id,
    ]);

    $section->load('teacher', 'school', 'students');
    return response([
      'section' => $section,
    ], 201);
  }

  public function delete(SectionRequest $request ){
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      'user_id' => $user->id,
    ])->first();

    $section = Section::where([
      'id' => $request->id,
      'teacher_id' => $teacher->id,
    ])->fist();

    if(!isset($section)) {
      return response([
        'error' => 'Illegal Access',
      ], 201);
    }
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
