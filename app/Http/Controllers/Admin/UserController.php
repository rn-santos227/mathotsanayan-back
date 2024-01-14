<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;

use App\Http\Requests\UserRequest;

class UserController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function delete(UserRequest $request) {
    $user = User::find($request->id);
    $user->delete();
    return response([
        'user' => $user,
    ], 201);
  }
}
