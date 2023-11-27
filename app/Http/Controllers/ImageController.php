<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function image(Request $request) {
        
    }
}
