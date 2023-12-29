<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Result;
use App\Models\School;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin() {
        $courses = Course::count();
        $modules = Module::count();
        $results = Result::where('completed', 1)
        ->whereHas('student', function ($query) {
            $query->whereNull('students.deleted_at');
        })
        ->count();
        $schools = School::count();
        $students = Student::count();
        $subjects = Subject::count();
        $teachers = Teacher::count(); 

        return response()->json([
        'dashboard' => [
            'courses' => $courses,
            'modules' => $modules,
            'results' => $results,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'subjects' => $subjects
        ]]);
    }

    public function teacher(Request $request) {

    }

    public function student(Request $request) {

    }
}
