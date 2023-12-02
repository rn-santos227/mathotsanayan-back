<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function submit(Request $request) {
        if($request->id) {

        } else return response([
            'error' => 'Illegal Access',
        ], 500);
    }
}
