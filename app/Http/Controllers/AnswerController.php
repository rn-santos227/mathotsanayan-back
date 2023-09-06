<?php

namespace App\Http\Controllers;

use App\Models\Answer;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        
    }
}
