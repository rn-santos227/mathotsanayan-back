<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\School;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin() {
        $modules = Module::count();
        $schools = School::count();
        $students = Student::count();
        $teachers = Teacher::count();
        $subjects = Subject::count(); 

        return response()->json([
            'modules' => $modules,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'subjects' => $subjects
        ]);
    }

    public function teacher(Request $request) {

    }

    public function student(Request $request) {

    }
}
