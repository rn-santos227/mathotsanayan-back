<?php

namespace App\Http\Controllers\Students;

use App\Models\Result;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResultRequest;

class ResultController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index(ResultRequest $request) {
    $results = Result::with('module', 'progress')
    ->where([
        'student_id' => $request->id,
        'completed' => 1,
        'invalidate' => 0,
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
