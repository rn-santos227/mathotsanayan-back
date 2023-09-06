<?php

namespace App\Http\Controllers;

use App\Models\Solution;

use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        
    }
}
