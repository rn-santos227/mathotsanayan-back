<?php

namespace App\Http\Controllers;

use App\Models\Admin;

use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $admins = Admin::get();
        return response()->json([
            'admins' => $admins
        ]);
    }

    public function create(AdminRequest $request) {

    }

    public function update(AdminRequest $request) {

    }

    public function delete(Request $request ){
        if($request->id) {

        }  else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
