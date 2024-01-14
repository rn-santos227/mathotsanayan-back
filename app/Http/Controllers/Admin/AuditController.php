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
    $audit = Audit::with('user')
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    return response([
      'audit' => $audit
  ], 200);
  }
}
