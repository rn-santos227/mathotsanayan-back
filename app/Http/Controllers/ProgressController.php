<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }
}
