<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }
}
