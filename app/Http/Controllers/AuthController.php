<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
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

        $admin = Admin::where([
            "user_id" => $user->id,
        ])->first();
        return User::getToken($user, $admin, "admin");
    }

    public function teacher(Request $request) {
        $fields = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password']) || $user->type > 2) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }

        $teacher = Teacher::where([
            "user_id" => $user->id,
        ])->first();
        return User::getToken($user, $teacher, "teacher");
    }

    public function student(Request $request) {
        $fields = User::validate($request);
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !$user->validatePassword($fields['password'])) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }
        
        $student = Student::where([
            "user_id" => $user->id,
        ])->first();
        return User::getToken($user, $student, "student");
    }

    public function auth() {
        return [
            'auth' => auth('sanctum')->check(),
        ];
    }

    public function user(Request $request) {
        if($request->type) {
            $user = auth('sanctum')->user();
            if($request->type == 1) {
                $admin = Admin::where([
                    "user_id" => $user->id,
                ])->first();
                return [
                    'user' => $user,
                    'admin' => $admin
                ];
            } else if ($request->tye == 2) {
                $teacher = Teacher::where([
                    "user_id" => $user->id,
                ])->first();
                return [
                    'user' => $user,
                    'teacher' => $teacher
                ];
            } else {
                $student = Student::where([
                    "user_id" => $user->id,
                ])->first();
                return [
                    'user' => $user,
                    'student' => $student
                ];
            }
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged Out'
        ];
    }
}
