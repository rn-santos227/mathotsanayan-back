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
        Result::with('answers', 'module', 'progress')
        ->makeVisible(['timer', 'completed', 'total_score'])
        ->get();

        return response([
            'results' => $results
        ], 200);
    }

    public function student(Request $request) {
        if (!$request->id) return response(['error' => 'Illegal Access'], 500);
        $results = Result::with('answers', 'module', 'progress')
        ->where('student_id', $request->id)
        ->makeVisible(['timer', 'completed', 'total_score'])
        ->get();
    
        return response([
            'results' => $results
        ], 200);
    }
}
