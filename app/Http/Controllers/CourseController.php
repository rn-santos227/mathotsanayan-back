<?php

namespace App\Http\Controllers;

use App\Models\Course;

use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;

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

    public function search(Request $request) {
        if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);

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
