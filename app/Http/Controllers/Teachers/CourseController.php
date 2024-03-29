<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Course;

use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $courses = Course::get();
    return response()->json([
      'courses' => $courses
    ]);
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
    if ($course->students()->count() > 0) {
      return response([
          'message' => 'Cannot delete course with students.',
      ], 400);
    }

    $course->delete();
    return response([
      'course' => $course,
    ], 201);
  }
}
