<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;

use App\Http\Requests\UserRequest;

use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordMail;

class UserController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $accounts = User::where('id', '!=', auth('sanctum')->user()->id)->orderBy('created_at', 'desc')->get();
    return response()->json([
        'accounts' => $accounts
    ]);
}

  public function reset(UserRequest $request) {
    $user = User::find($request->id);
    $user->update([
      'password' => $request->password,
    ]);

    $username = $request->email;
    $password = $request->password;
    Mail::to($request->email)->send(new PasswordMail($username, $password));

    return response([
      'user' => $user,
    ], 201);
  }

  public function delete(UserRequest $request) {
    $user = User::find($request->id);
    $user->delete();
    return response([
        'user' => $user,
    ], 201);
  }
}
