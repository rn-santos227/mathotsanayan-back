<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditRequest;

class AuditController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function admin(AuditRequest $request) {

    }

    public function teacher(AuditRequest $request) {
        $user = auth('sanctum')->user();
    }

    public function student(AuditRequest $request) {
        $user = auth('sanctum')->user();
    }
}
