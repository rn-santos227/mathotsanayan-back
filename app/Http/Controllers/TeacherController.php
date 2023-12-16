<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;

use App\Http\Requests\TeacherRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\TeacherMail;

class TeacherController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function index() {
        $teachers = Teacher::with('school')->get();
        return response()->json([
            'teachers' => $teachers
        ]);
    }

    public function create(TeacherRequest $request) {
        $request->validated();
        $user = User::create([
            'type' => 2,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $teacher = Teacher::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'school_id' => $request->school,
            'user_id' => $user->id,
        ])->load('school');

        $username = $request->email;
        $password = $request->password;
    
        Mail::to($request->email)->send(new TeacherMail($username, $password));

        return response([
            'teacher' => $teacher,
        ], 201);
    }

    public function update(TeacherRequest $request) {
        if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

        $request->validated();
        $teacher = Teacher::find($request->id);
        if(!$teacher) return response(['error' => 'Illegal Access',], 500); 
        
        if(!empty($request->password)) {
            $teacher->makeVisible('user_id');
            $user = User::find($teacher->user_id);
            $user->update([
                'email' => $request->email,
                'password' => $request->password,
            ]);
        }
        
        $teacher->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'school_id' => is_numeric($request->school) ? $request->school['id'] : $request->school_id,
        ]);
        
        $teacher->load('school');
        return response([
            'teacher' => $teacher,
        ], 201);
    }

    public function delete(Request $request ){
        if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

        $teacher = Teacher::find($request->id);
        if(!$teacher) return response(['error' => 'Illegal Access',], 500); 

        $teacher->delete();
        return response([
            'teacher' => $teacher,
        ], 201);
    }
}
