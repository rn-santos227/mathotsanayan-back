<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function admin(Request $request) {
        $field = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 1) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return User::getToken();
    }

    public function teacher(Request $request) {
        $field = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 2) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return User::getToken();
    }

    public function student(Request $request) {
        $field = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password'])) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return User::getToken();
    }

    public function auth(Request $request) {
        return [
            'auth' => auth('sanctum')->check()
        ];
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged Out'
        ];
    }
}
