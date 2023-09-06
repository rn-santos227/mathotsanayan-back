<?php

namespace App\Http\Controllers;

use App\Models\Student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $students = Student::get();
        return response()->json([
            'students' => $students
        ]);
    }
}
