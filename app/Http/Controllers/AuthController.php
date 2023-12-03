<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function admin(Request $request) {
        $fields = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 1) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }

        return User::getToken($user, "admin");
    }

    public function teacher(Request $request) {
        $fields = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 2) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return User::getToken($user, "teacher");
    }

    public function student(Request $request) {
        $fields = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password'])) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return User::getToken($user, "student");
    }

    public function auth() {
        return [
            'auth' => auth('sanctum')->check(),
        ];
    }

    public function user() {
        return [
            'user' => auth('sanctum')->user(),
        ];
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged Out'
        ];
    }
}
