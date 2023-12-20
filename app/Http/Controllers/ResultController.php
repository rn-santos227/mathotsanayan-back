<?php

namespace App\Http\Controllers;

use App\Models\Result;

use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        Result::with('answers', 'answers.question', 'answers.grade', 'module', 'progress')
        ->where([
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

    public function student(Request $request) {
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);
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
