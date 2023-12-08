<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }
}
