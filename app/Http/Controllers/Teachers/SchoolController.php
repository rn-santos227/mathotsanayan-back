<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\School;

use App\Http\Requests\SchoolRequest;

class SchoolController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $schools = School::orderBy('created_at', 'desc')->get();
    return response()->json([
      'schools' => $schools
    ]);
  }
}
