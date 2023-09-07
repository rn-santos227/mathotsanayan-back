<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function admin(Request $request) {
        $field = $this->validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 1) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return $this->getToken();
    }

    public function teacher(Request $request) {
        $field = $this->validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 2) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return $this->getToken();
    }

    public function student(Request $request) {
        $field = $this->validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password'])) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        return $this->getToken();
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

    private function validate(Request $request) {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);
    }

    private function getToken() {
        $token = $user->createToken(env('PASSWORD_SALT'))->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
