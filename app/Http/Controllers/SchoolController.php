<?php

namespace App\Http\Controllers;

use App\Models\School;

use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $schools = School::get();
        return response()->json([
            'schools' => $schools
        ]);
    }
}
