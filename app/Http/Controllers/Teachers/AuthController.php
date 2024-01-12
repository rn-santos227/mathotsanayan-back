<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Teacher;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function index(Request $request) {
    $fields = User::validate($request);
    $user = User::where('email', $fields['email'])->first();

    if(!$user || !$user->validatePassword($fields['password']) || $user->type != 1) {
      return response([
        'message' => 'Bad Credentials'
      ], 401);
    }

    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();
    return $user->getToken($user, $teacher, "teacher");
  }

  public function user(Request $request) {
    $user = auth('sanctum')->user();
    if ($user->type != 1) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $teacher = Teacher::where('user_id', $user->id)->first();
    return ['teacher' => $teacher];
  }
}
