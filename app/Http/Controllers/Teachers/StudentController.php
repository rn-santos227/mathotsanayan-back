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

    $section = Section::where('id', $request->section)
    ->where('teacher_id', $teacher->id)
    ->first();

    if (!$section) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $user = User::create([
      'type' => 3,
      'email' => $request->email,
      'password' => $request->password,
    ]);

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

  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $students = Student::with('section', 'school', 'course')
    ->whereHas('section', function ($query) use ($teacher) {
      $query->where('teacher_id', $teacher->id);
    })
    ->where(function ($query) use ($request) {
      $category = $request->query('category');
      $search = $request->query('search');

      switch ($category) {
        case 'full_name':
          $query->whereRaw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(suffix, ''), ' ', UPPER(SUBSTRING(middle_name, 1, 1))) LIKE ?", ['%' . $search . '%']);
          break;

        case 'email':
          $query->where('email', 'like', '%' . $search . '%');
          break;

        case 'school.name':
        case 'section.name':
          $relation = $category == 'school.name' ? 'school' : 'section';
          $query->whereHas($relation, function ($query) use ($search, $category) {
              $query->where('name', 'like', '%' . $search . '%');
          });
          break;
      }
    })
    ->orderBy('created_at', 'desc')
    ->get();

    return response()->json([
      'students' => $students,
    ]);
  }

  public function update(StudentRequest $request) {
    $request->validated();
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $section = Section::where('id', is_numeric($request->section)  ? $request->section['id'] : $request->section_id)
    ->where('teacher_id', $teacher->id)
    ->first();

    if (!$section) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $student = Student::find($request->id);
    $student->makeVisible('user_id');
    if(!empty($request->password)) {
      $user = User::find($student->user_id);
      $user->update([
        'email' => $request->email,
        'password' => $request->password,
      ]);
    }

    $student->update([
      'first_name' => $request->first_name,
      'middle_name' => $request->middle_name,
      'last_name' => $request->last_name,
      'suffix' => $request->suffix,
      'email' => $request->email,
      'contact_number' => $request->contact_number,
      'student_number' => $request->student_number,
      'course_id' => is_numeric($request->course) ? $request->course['id'] : $request->course_id,
      'school_id' => is_numeric($request->school) ? $request->school['id'] : $request->school_id,
      'section_id' => $section->id,
    ]);
    
    $student->load('school', 'section', 'course');
    return response([
      'student' => $student,
    ], 201);
  }

  public function delete(StudentRequest $request){
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      'user_id' => $user->id,
    ])->first();

    $student = Student::where('id', $request->id)
    ->whereHas('section', function ($query) use ($teacher) {
        $query->where('teacher_id', $teacher->id);
    })->first();

    $student->delete();
    return response([
      'student' => $student,
    ], 201);
  }
}
