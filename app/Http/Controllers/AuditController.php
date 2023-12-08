<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }
}
