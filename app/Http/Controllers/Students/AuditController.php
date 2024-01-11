<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;

use App\Models\Audit;
use App\Models\User;

use Illuminate\Http\Request;

class AuditController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
  }
}
