<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Audit;

use Illuminate\Http\Request;

class AuditController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
  }
}
