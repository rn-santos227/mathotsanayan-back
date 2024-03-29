<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Student;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function index(Request $request) {
    $fields = User::validate($request);
    $user = User::where('email', $fields['email'])->first();

    if(!$user || !$user->validatePassword($fields['password']) || $user->type != 3) {
      return response([
        'message' => 'Bad Credentials'
      ], 401);
    }

    $student = Student::with('section','school','course','section.teacher')->where([
      "user_id" => $user->id,
    ])->first();
    return $user->getToken($user, $student, "student");
  }

  public function user(Request $request) {
    $user = auth('sanctum')->user();
    if ($user->type != 3) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $student = Student::where('user_id', $user->id)->first();
    $student->load('course','section','school','section.teacher');
    return ['student' => $student];
  }
}
