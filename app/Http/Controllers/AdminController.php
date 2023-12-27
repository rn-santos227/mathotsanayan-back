<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;

use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\StudentMail;

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

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'user_id' => $user->id,
        ]);

        $username = $request->email;
        $password = $request->password;

        Mail::to($request->email)->send(new AdminMail($username, $password));

        return response([
            'admin' => $admin,
        ], 201);

    }

    public function update(AdminRequest $request) {
        $request->validated();
        $admin = Admin::find($request->id);
    }

    public function delete(AdminRequest $request ){
        $admin = Admin::find($request->id);
    }
}
