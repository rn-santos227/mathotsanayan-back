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
        $subjects = Subject::with("modules")->get();
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
        if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

        $request->validated();
        $subject = Subject::find($request->id);
        if(!$subject) return response(['error' => 'Illegal Access',], 500); 

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

    public function delete(Request $request) {
        if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

        $subject = Subject::find($request->id);
        if(!$subject) return response(['error' => 'Illegal Access',], 500); 

        $subject->delete();
        return response([
            'subject' => $subject,
        ], 201);
    }
}
