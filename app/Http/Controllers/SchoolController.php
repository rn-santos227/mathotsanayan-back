<?php

namespace App\Http\Controllers;

use App\Models\School;

use App\Http\Requests\SchoolRequest;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $schools = School::get();
        return response()->json([
            'schools' => $schools
        ]);
    }

    public function search(Request $request) {
        if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);

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
        $school->delete();
        return response([
            'school' => $school,
        ], 201);
    }
}
