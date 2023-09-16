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

    public function create(SchoolRequest $request) {

    }

    public function update(SchoolRequest $request) {

    }

    public function delete(Request $request ){
        
    }
}
