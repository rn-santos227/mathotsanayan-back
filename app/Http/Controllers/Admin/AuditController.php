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

  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $audit = Audit::with('user')
    ->where(function($query) use($request) {
      $category = $request->query('category');
      $search = $request->query('search');
      switch ($category) {
        case 'user.full_name';
          $query->whereHas('user', function ($userQuery) use ($search) {
            $userQuery->whereHas('owner', function ($ownerQuery) use ($search) {
                $ownerQuery->whereRaw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(suffix, ''), ' ', UPPER(SUBSTRING(middle_name, 1, 1))) LIKE ?", ['%' . $search . '%']);
            });
          });
          break;

        case 'activity':
          $query->where('activity', 'like', '%' . $search . '%');
          break;

        case 'content':
          $query->where('content', 'like', '%' . $search . '%');
          break;

        case 'date':
          if (preg_match('/^\d{4}(-\d{2})?$/', $search)) {
              $query->where('created_at', 'like', $search . '%');
          } else {
              $query->whereDate('created_at', now()->toDateString());
          }
          break;

        case 'table':
          $query->where('table', 'like', '%' . $search . '%');
          break;
      }
    })
    ->orderBy('created_at', 'desc')
    ->get();

    foreach ($audit as $auditRecord) {
      $content = json_decode($auditRecord->content, true);
      if (is_array($content) && array_key_exists('password', $content)) {
        unset($content['password']);
        $auditRecord->content = json_encode($content);
      }
    }

    return response()->json([
      'audit' => $audit,
    ]);
  }
}
