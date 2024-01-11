<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;

use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\StudentMail;

class StudentController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $students = Student::with('section','school','course')
    ->where(function($query) use($teacher) {
      $query->whereHas('section', function ($query) use ($teacher) {
        $query->where([
          'teacher_id' => $teacher->id
        ]);
      });
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    return response()->json([
      'students' => $students
    ]);
  }

  public function create(StudentRequest $request) {
    $request->validated();
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $user = User::create([
      'type' => 3,
      'email' => $request->email,
      'password' => $request->password,
    ]);

    $section = Section::where([
      'teacher_id' => $teacher->id,
    ])->first();

    $student = Student::create([
      'first_name' => $request->first_name,
      'middle_name' => $request->middle_name,
      'last_name' => $request->last_name,
      'suffix' => $request->suffix,
      'email' => $request->email,
      'contact_number' => $request->contact_number,
      'student_number' => $request->student_number,
      'course_id' => $request->course,
      'school_id' => $request->school,
      'section_id' => $section->id,
      'user_id' => $user->id,
    ])->load('school', 'section', 'course');

    $username = $request->email;
    $password = $request->password;

    Mail::to($request->email)->send(new StudentMail($username, $password));

    return response([
      'student' => $student,
    ], 201);
  }
}
