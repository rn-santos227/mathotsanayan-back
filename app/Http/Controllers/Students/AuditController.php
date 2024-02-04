<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;

use App\Models\Audit;

use Illuminate\Http\Request;

class AuditController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $audit = Audit::where([
      'user_id' => $user->id
    ])
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    foreach ($audit as $auditRecord) {
      $content = json_decode($auditRecord->content, true);
      if (is_array($content) && array_key_exists('password', $content)) {
        unset($content['password']);
        $auditRecord->content = json_encode($content);
      }
    }

    return response([
      'audit' => $audit
    ], 200);
  }
}
