<?php

namespace App\Http\Controllers;

use App\Models\Section;

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
}
