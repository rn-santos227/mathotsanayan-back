<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Student;
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
    $students = Student::with('section','school','course')->orderBy('created_at', 'desc')->paginate(10);
    return response()->json([
      'students' => $students
    ]);
  }
  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $students = Student::with('section','school','course')
    ->where(function($query) use($request) {
      $category = $request->query('category');
      $search = $request->query('search');
      switch ($category) {
      case 'full_name':
        $search = '%' . $search . '%';   
        $query->where(function ($query) use ($search) {
          $query->where('last_name', 'LIKE', $search)
          ->orWhere('first_name', 'LIKE', $search)
          ->orWhere('suffix', 'LIKE', $search)
          ->orWhereRaw("UPPER(SUBSTRING(middle_name, 1, 1)) LIKE ?", [strtoupper(substr($search, 1, 1))]);
        });
        break;

      case 'course.name':
        $query->whereHas('course', function ($query) use ($search) {
          $query->where('name', 'like', '%' . $search . '%');
        });
        break;

      case 'email':
        $query->where('email', 'like', '%' . $search . '%');
        break;

        case 'school.name':
        case 'section.name':
          $relation = $category == 'school.name' ? 'school' : 'section';
          $query->whereHas($relation, function ($query) use ($search) {
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

  public function create(StudentRequest $request) {
    $request->validated();
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
      'section_id' => $request->section,
      'user_id' => $user->id,
    ])->load('school', 'section', 'course');

    $username = $request->email;
    $password = $request->password;

    Mail::to($request->email)->send(new StudentMail($username, $password));

    return response([
      'student' => $student,
    ], 201);
  }

  public function update(StudentRequest $request) {
    $student = Student::find($request->id);
    $student->makeVisible('user_id');

    $student->update([
      'first_name' => $request->first_name,
      'middle_name' => $request->middle_name,
      'last_name' => $request->last_name,
      'suffix' => $request->suffix,
      'email' => $request->email,
      'contact_number' => $request->contact_number,
      'student_number' => $request->student_number,
      'course_id' => is_numeric($request->course) ? $request->course : $request->course['id'],
      'school_id' => is_numeric($request->school) ? $request->school : $request->school['id'],
      'section_id' => is_numeric($request->section) ? $request->section : $request->section['id'],
    ]);
    
    $student->load('school', 'section', 'course');
    return response([
      'student' => $student,
    ], 201);
  }

  public function delete(StudentRequest $request){
    $student = Student::find($request->id);
    if ($student->results()->count() > 0) {
      return response([
        'message' => 'Cannot delete student with results.',
      ], 400);
    }

    $user = User::find($student->user_id);

    $student->delete();
    $user->delete();

    return response([
      'student' => $student,
    ], 201);
  }
}
