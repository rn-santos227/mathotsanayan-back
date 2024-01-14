<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Course;

use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }
}
