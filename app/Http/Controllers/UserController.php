<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {

    }

    public function delete(Request $request) {
        if($request->id) {

        }  else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
