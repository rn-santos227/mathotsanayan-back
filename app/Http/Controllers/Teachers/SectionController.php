<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Section;
use App\Models\Teacher;

use Illuminate\Http\Request;

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

    $sections = Section::where([
      'teacher_id' => $teacher->id
    ])->get();

    return response()->json([
      'sections' => $sections
    ]);
  }
}
