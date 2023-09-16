<?php

namespace App\Http\Controllers;

use App\Models\Section;

use App\Http\Requests\SectionRequest;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum, admin');
    }

    public function index() {
        $sections = Section::get();
        return response()->json([
            'sections' => $sections
        ]);
    }

    public function create(SectionRequest $request) {

    }

    public function update(SectionRequest $request) {

    }

    public function delete(Request $request ){
        
    }
}
