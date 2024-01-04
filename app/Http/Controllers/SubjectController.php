<?php

namespace App\Http\Controllers;

use App\Models\Subject;

use App\Http\Requests\SubjectRequest;
use Illuminate\Http\Request;

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

    public function student() {
        $subjects = Subject::whereHas('modules', function ($query) {
            $query->where('active', 1);
        })->has('modules')->get();

        return response()->json([
            'subjects' => $subjects
        ]);
    }

    public function search(Request $request) {
        if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);

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
