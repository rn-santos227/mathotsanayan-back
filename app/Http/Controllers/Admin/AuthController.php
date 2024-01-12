<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function index(Request $request) {
    $fields = User::validate($request);
    $user = User::where('email', $fields['email'])->first();

    if(!$user || !$user->validatePassword($fields['password']) || $user->type > 1) {
      return response([
        'message' => 'Bad Credentials'
      ], 401);
    }

    $admin = Admin::where([
      "user_id" => $user->id,
    ])->first();
    return $user->getToken($user, $admin, "admin");
  }

  public function user(Request $request) {
    $user = auth('sanctum')->user();
    if ($user->type != 1) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $admin = Admin::where('user_id', $user->id)->first();
    return ['admin' => $admin];
  }

  public function auth() {
    return [
      'auth' => auth('sanctum')->check(),
    ];
  }

  public function logout(Request $request) {
    $request->user()->tokens()->delete();
    return [
      'message' => 'Logged Out'
    ];
  }
}
