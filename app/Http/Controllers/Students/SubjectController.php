<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Subject;

class SubjectController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $subjects = Subject::whereHas('modules', function ($query) {
      $query->where('active', 1);
    })->has('modules')->get();

    return response()->json([
      'subjects' => $subjects
    ]);
  }
}
