<?php

namespace App\Http\Controllers;

use App\Models\Grade;

use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $grades = Grade::get();
        return response()->json([
            'grades' => $grades
        ]);
    }
}
