<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $accounts = User::all();
        return response()->json([
            'accounts' => $accounts
        ]);
    }

    public function password(UserRequest $request) {
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

    public function delete(UserRequest $request) {
        $user = User::find($request->id);
        $user->delete();
        return response([
            'user' => $user,
        ], 201);
    }
}
