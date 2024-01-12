<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\User;

use App\Http\Requests\AdminRequest;

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;

class AdminController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $admins = Admin::where('user_id', '!=', auth('sanctum')->user()->id)->orderBy('created_at', 'desc')->get();
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
    if(!empty($request->password)) {
        $user = User::find($admin->user_id);
        $user->update([
            'email' => $request->email,
            'password' => $request->password,
        ]);
    }

    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
        'contact_number' => $request->contact_number,
    ]);

    return response([
        'admin' => $admin,
    ], 201);
  }

  public function delete(AdminRequest $request ){
    $admin = Admin::find($request->id);
    $admin->delete();
    return response([
        'admin' => $admin,
    ], 201);
  }
}
