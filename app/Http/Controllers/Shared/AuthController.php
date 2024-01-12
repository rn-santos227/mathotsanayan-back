<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function auth() {
    return [
      'auth' => auth('sanctum')->check(),
    ];
  }

  public function password(Request $request) {
    $user = auth('sanctum')->user();
    if(!$user || !$user->validatePassword($request->current_password)) {
        return response([
            'message' => 'Bad Credentials'
        ], 401);
    }

    $user->update([
        'password' => $request->password,
    ]);

    return response([
        'message' => "update successful",
    ], 201);
}

  public function logout(Request $request) {
    $request->user()->tokens()->delete();
    return [
      'message' => 'Logged Out'
    ];
  }
}
