<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Result;

use App\Http\Requests\ResultRequest;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $results = Result::with('answers', 'answers.question', 'answers.grade', 'module', 'progress', 'student', 'student.section', 'student.school')
        ->where([
            'completed' => 1,
        ])
        ->get();
    
        return response([
            'results' => $results
        ], 200);
    }

    public function student(ResultRequest $request) {
        $results = Result::with('answers', 'answers.question', 'answers.grade', 'module', 'progress')
        ->where([
            'student_id' => $request->id,
            'completed' => 1,
        ])
        ->whereHas('module', function ($query) {
            $query->where('active', 1);
        })
        ->get();

        $results->makeVisible(['timer', 'completed', 'total_score']);
    
        return response([
            'results' => $results
        ], 200);
    }
}
