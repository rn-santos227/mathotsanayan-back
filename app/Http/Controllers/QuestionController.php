<?php

namespace App\Http\Controllers;

use App\Models\Question;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        
    }
}
