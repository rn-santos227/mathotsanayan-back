<?php

namespace App\Http\Controllers;

use App\Models\Option;

use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        
    }
}
