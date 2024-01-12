<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Subject;

use App\Http\Requests\SubjectRequest;

class SubjectController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $subjects = Subject::with("modules")->orderBy('created_at', 'desc')->get();
    return response()->json([
      'subjects' => $subjects
    ]);
  }

  public function create(SubjectRequest $request) {
    $request->validated();
    $subject = Subject::create(
      $request->only([
        "name",
        "description",
      ])
    )->load('modules');

    return response([
      'subject' => $subject,
    ], 201);
  }

  public function update(SubjectRequest $request) {
    $request->validated();
    $subject = Subject::find($request->id);
    $subject->update(
      $request->only([
        "name",
        "description",
      ])
    );
    $subject->load('modules');
    return response([
      'subject' => $subject,
    ], 201);
  }

  public function delete(SubjectRequest $request) {
    $subject = Subject::find($request->id);
    $subject->delete();
    return response([
      'subject' => $subject,
    ], 201);
  }
}
