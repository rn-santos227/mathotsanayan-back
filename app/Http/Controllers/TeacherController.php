<?php

namespace App\Http\Controllers;

use App\Models\Teacher;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum, admin');
    }

    public function index() {
        $teachers = Teacher::get();
        return response()->json([
            'teachers' => $teachers
        ]);
    }
}
