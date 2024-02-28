<?php

namespace App\Http\Controllers\Teachers;
use App\Http\Controllers\Controller;

use App\Models\Result;
use App\Models\Teacher;

use App\Http\Requests\ResultRequest;
use Illuminate\Http\Request;

class ResultController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $results = Result::with('module', 'progress', 'student', 'student.section', 'student.school')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
    ])
    ->whereHas('student', function ($query) use ($teacher) {
        $query->whereNull('students.deleted_at')
        ->whereHas('section', function($query) use ($teacher) {
          $query->where('teacher_id', $teacher->id);
        });
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    $results->each(function ($result) {
      $result->makeVisible(['timer', 'completed', 'total_score', 'grade']);
    });
    return response([
        'results' => $results
    ], 200);
  }

  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $results = Result::with('module', 'progress', 'student', 'student.section', 'student.school')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
    ])->where(function ($query) use ($request) {
      $category = $request->query('category');
      $search = $request->query('search');
      switch ($category) {
        case 'module.name':
          $query->whereHas('module', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
          });
          break;

        case 'student.full_name':
          $query->whereHas('student', function ($query) use ($search) {
            $search = '%' . $search . '%';   
            $query->where(function ($query) use ($search) {
              $query->where('last_name', 'LIKE', $search)
              ->orWhere('first_name', 'LIKE', $search)
              ->orWhere('suffix', 'LIKE', $search)
              ->orWhereRaw("UPPER(SUBSTRING(middle_name, 1, 1)) LIKE ?", [strtoupper(substr($search, 1, 1))]);
            });
          });
          break;

        case 'module.subject':
          $query->whereHas('module.subject', function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
          });
          break;

        case 'student.course':
          $query->whereHas('student.course', function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
          });
          break;

        case 'student.section':
          $query->whereHas('student.section', function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
          });
          break;

        case 'student.school':
          $query->whereHas('student.school', function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
          });
          break;
      }
      $query->whereHas('student', function ($query) {
        $query->whereNull('students.deleted_at');
      });
    })->whereHas('student', function ($query) use ($teacher) {
      $query->whereNull('students.deleted_at')
      ->whereHas('section', function($query) use ($teacher) {
        $query->where('teacher_id', $teacher->id);
      });
    })->orderBy('created_at', 'desc')
    ->get();

    $results->makeVisible(['timer', 'completed', 'total_score', 'grade']);
    return response([
        'results' => $results
    ], 200);
  }

  public function invalidate(ResultRequest $request ){
    $user = auth('sanctum')->user();
    $teacher = Teacher::where([
      "user_id" => $user->id,
    ])->first();

    $result = Result::where([
      'id' => $request->id
    ])->whereHas('student', function ($query) use ($teacher) {
      $query->whereNull('students.deleted_at')
      ->whereHas('section', function($query) use ($teacher) {
        $query->where('teacher_id', $teacher->id);
      });
    })->first();

    if(!$result) return response(['error' => 'Illegal Access'], 500);
    
    $result->update([
      'invalidate' => 1,
    ]);
    return response([
        'result' => $result,
    ], 201);
  }
}
