<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function submit() {
        
    }
}
