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
        
    }
}
