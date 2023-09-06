<?php

namespace App\Http\Controllers;

use App\Models\Course;

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
}
