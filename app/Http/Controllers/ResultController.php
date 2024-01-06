<?php

namespace App\Http\Controllers;

use App\Models\Result;

use App\Http\Requests\ResultRequest;
use Illuminate\Http\Request;

class ResultController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function index() {
    $results = Result::with('answers', 'answers.question', 'answers.grade', 'module', 'progress', 'student', 'student.section', 'student.school')
    ->where([
        'completed' => 1,
        'invalidate' => 0,
    ])
    ->whereHas('student', function ($query) {
        $query->whereNull('students.deleted_at'); 
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    $results->each(function ($result) {
      $result->makeVisible(['timer', 'completed', 'total_score']);
    });
    return response([
        'results' => $results
    ], 200);
  }

  public function search(Request $request) {
    if(!$request->query('category')) return response(['error' => 'Illegal Access'], 500);
    $results = Result::with('answers', 'answers.question', 'answers.grade', 'module', 'progress', 'student', 'student.section', 'student.school')
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
            $query->whereRaw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(suffix, ''), ' ', UPPER(SUBSTRING(middle_name, 1, 1))) LIKE ?", ['%' . $search . '%']);
          });
          break;

        case 'module.subject':
          $query->whereHas('module.subject', function ($query) use ($search) {
              $query->where('name', 'like', '%' . $search . '%');
          });
          break;

        case 'student.section':
          $query->whereHas('student.section', function ($query) use ($search) {
              $query->where('name', 'like', '%' . $search . '%');
          });
          break;

        case 'student.school':
          $query->whereHas('student.school', function ($query) use ($search) {
              $query->where('name', 'like', '%' . $search . '%');
          });
          break;
      }
      $query->whereHas('student', function ($query) {
        $query->whereNull('students.deleted_at');
      });
    })
    ->orderBy('created_at', 'desc')
    ->get();

    $results->makeVisible(['timer', 'completed', 'total_score']);
    return response([
        'results' => $results
    ], 200);
  }

  public function student(ResultRequest $request) {
    $results = Result::with('answers', 'answers.question', 'answers.grade', 'module', 'progress')
    ->where([
        'student_id' => $request->id,
        'completed' => 1,
        'invalidate' => 0,
    ])
    ->whereHas('module', function ($query) {
        $query->where('active', 1);
    })
    ->get();
    $results->makeVisible(['timer', 'completed', 'total_score']);
    return response([
        'results' => $results
    ], 200);
  }

  public function invalidate(ResultRequest $request ){
    $result = Result::find($request->id);
    $result->update([
      'invalidate' => 1,
    ]);
    return response([
        'result' => $result,
    ], 201);
}
}
