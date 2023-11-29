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
        if($request->id) {
            $request->validated();
            $subject = Subject::find($request->id);
            $subject->update(
                $request->only([
                    "name",
                    "description",
                ])
            );
            $section->load('modules');
            return response([
                'subject' => $subject,
            ], 201);
        }  else return response([
            'error' => 'Illegal Access',
        ], 500);
    }

    public function delete(Request $request) {
        if($request->id) {
            $subject = Subject::find($request->id);
            $subject->delete();
            return response([
                'subject' => $subject,
            ], 201);
        } 
        else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
