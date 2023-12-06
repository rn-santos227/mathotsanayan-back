<?php

namespace App\Http\Controllers;

use App\Models\Option;

use App\Http\Requests\OptionRequest;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function create(OptionRequest $request) {
        if($request->id) {
            $request->validated();

        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function update(OptionRequest $request) {
        if($request->id) {
            $request->validated();
            
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function delete(Request $request ) { 
        if($request->id) {
            $option = Option::find($request->id);
            $option->delete();
            return response([
                'option' => $option,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
