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

  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $audit = Audit::with('user')
    ->where(function($query) use($request) {
      $category = $request->query('category');
      $search = $request->query('search');
      switch ($category) {
        case 'activity':
          $query->where('activity', 'like', '%' . $search . '%');
          break;

        case 'content':
          $query->where('content', 'like', '%' . $search . '%');
          break;

        case 'created_at':
          $query->where('created_at', 'like', '%' . $search . '%');
          break;

        case 'table':
          $query->where('table', 'like', '%' . $search . '%');
          break;
      }
    })
    ->orderBy('created_at', 'desc')
    ->get();

    return response()->json([
      'audit' => $audit,
    ]);
  }
}
