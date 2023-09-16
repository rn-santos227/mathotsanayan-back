<?php

namespace App\Http\Controllers;

use App\Models\Module;

use App\Http\Requests\ModuleRequest;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $modules = Module::get();
        return response()->json([
            'modules' => $modules
        ]);
    }

    public function create(ModuleRequest $request) {

    }

    public function update(ModuleRequest $request) {

    }

    public function delete(Request $request ){
        
    }
}
