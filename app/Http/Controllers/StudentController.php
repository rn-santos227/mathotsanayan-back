<?php

namespace App\Http\Controllers;

use App\Models\Student;

use App\Http\Requests\StudentRequest;
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

    public function create(StudentRequest $request) {

    }

    public function update(StudentRequest $request) {

    }

    public function delete(Request $request ){
        
    }
}
