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
        $courses = Course::with("modules")->get();
        return response()->json([
            'courses' => $courses
        ]);
    }

    public function create(CourseRequest $request) {
        $request->validated();
        $course = Course::create(
            $request->only([
                "name",
                "destination",
            ])
        );

        return response([
            'course' => $course,
        ], 201);
    }

    public function update(CourseRequest $request) {
        $request->validated();
        if($request->id) {
            $course = Course::find($request->id);
            $course->update(
                $request->only([
                    "name",
                    "destination",
                ])
            );
            return response([
                'course' => $course,
            ], 201);
        }  else return response([
            'error' => 'Illegal Access',
        ], 500);
    }

    public function delete(Request $request) {
        if($request->id) {
            $course = Course::find($request->id);
            $course->delete();
            return response([
                'course' => $course,
            ], 201);
        } 
        else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
