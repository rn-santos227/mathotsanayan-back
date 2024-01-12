<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Course;

use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function create(CourseRequest $request) {
    $request->validated();
    $course = Course::create(
      $request->only([
        "name",
        "abbreviation",
        "description",
      ])
    );

    return response([
      'course' => $course,
    ], 201);
  }

  public function update(CourseRequest $request) {
    $request->validated();
    $course = Course::find($request->id);
    $course->update(
      $request->only([
        "name",
        "abbreviation",
        "description",
      ])
    );
    return response([
      'course' => $course,
    ], 201);
  }

public function delete(CourseRequest $request) {
    $course = Course::find($request->id);
    $course->delete();
    return response([
      'course' => $course,
    ], 201);
  }
}
