<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function email(Request $request) {
    
  }

  public function delete(UserRequest $request) {
    $user = User::find($request->id);
    $user->delete();
    return response([
        'user' => $user,
    ], 201);
  }
}
