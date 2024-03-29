<?php

namespace App\Http\Controllers\Admin;
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

  public function create(SchoolRequest $request) {
    $request->validated();
    $school = School::create([
      'name' => $request->name,
      'email' => $request->email,
      'address' => $request->address,
      'contact_number' => $request->contact_number,
      'description' => $request->description
    ]);

    return response([
      'school' => $school,
    ], 201);
  }

  public function update(SchoolRequest $request) {
    $request->validated();
    $school = School::find($request->id);
    $school->update([
      'name' => $request->name,
      'email' => $request->email,
      'address' => $request->address,
      'contact_number' => $request->contact_number,
      'description' => $request->description
    ]);
    return response([
      'school' => $school,
    ], 201);
  }

  public function delete(SchoolRequest $request ){
    $school = School::find($request->id);
    if ($school->sections()->count() > 0) {
      return response([
          'message' => 'Cannot delete school with sections.',
      ], 400);
    }

    $school->delete();
    return response([
      'school' => $school,
    ], 201);
  }
}
