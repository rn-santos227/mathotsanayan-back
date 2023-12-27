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
        $request->validated();
        $user = User::create([
            'type' => 1,
            'email' => $request->email,
            'password' => $request->password,
        ]);

    }

    public function update(AdminRequest $request) {
        $request->validated();
    }

    public function delete(AdminRequest $request ){

    }
}
