<?php

namespace App\Http\Controllers;

use App\Models\Correct;

use App\Http\Requests\CorrectRequest;
use Illuminate\Http\Request;

class CorrectController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function create(CorrectRequest $request) {

    }

    public function update(CorrectRequest $request) {
        if($request->id) {

        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function delete(Request $request ) { 
        if($request->id) {

        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
