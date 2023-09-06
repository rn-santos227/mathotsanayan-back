<?php

namespace App\Http\Controllers;

use App\Models\Admin;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $admins = Admin::get();
        return response()->json([
            'admins' => $admins
        ]);
    }
}
