<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Question;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function questions(Request $request) {
        if($request->id) {

        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
