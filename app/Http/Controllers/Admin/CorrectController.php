<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\Question;
use App\Models\Correct;

use Illuminate\Http\Request;


class CorrectController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function create(Request $request) {
    if (!$request->id) return response(['error' => 'Illegal Access'], 500);
    $question = Question::find($request->id);
    if (!$question) return response(['error' => 'Illegal Access'], 500);

    $payload_correct = json_decode($request->correct, true);
    $correct = Correct::create([
      'content' => $payload_correct['content'],
      'solution' => $payload_correct['solution'],
      'module_id' => $question->module_id,
      'subject_id' => $question->subject_id,
      'question_id' => $question->id,
    ]);

    $file = $request->file('correct_file');
    if(isset($file)) {
      $file_url = '/corrects/correct-'.$correct->id.'/correct-'.$correct->id.'.'.$file->getClientOriginalExtension();
      if (Storage::disk('minio')->exists($file_url)) {
        Storage::disk('minio')->delete($file_url);
      }
      
      $correct->update(['file' => $file_url]);
      Storage::disk('minio')->put($file_url, file_get_contents($file));
    }

    $questions = Question::with('corrects', 'options')->where([
      'module_id' => $question->module_id,
    ])->get();

    return response([
      'questions' => $questions,
    ], 201);
  }

  public function update(Request $request) {
    if (!$request->id) return response(['error' => 'Illegal Access'], 500);
    $payload_correct = json_decode($request->correct, true);

    $correct = Correct::find($request->id);
    if (!$correct) return response(['error' => 'Illegal Access'], 500);

    $correct->update([
      'content' => $payload_correct['content'],
      'solution' => $payload_correct['solution'],
    ]);

    $file = $request->file('correct_file');
    if(isset($file)) {
      $file_url = '/corrects/correct-'.$correct->id.'/correct-'.$correct->id.'.'.$file->getClientOriginalExtension();

      if (Storage::disk('minio')->exists($file_url)) {
        Storage::disk('minio')->delete($file_url);
      }
      
      $correct->update(['file' => $file_url]);
      Storage::disk('minio')->put($file_url, file_get_contents($file));
    }
    
    $question = Question::find($correct->question_id);
    $questions = Question::with('corrects', 'options')->where([
      'module_id' => $question->module_id,
    ])->get();

    return response([
      'questions' => $questions,
    ], 201);
  }

  public function delete(Request $request ) { 
    if (!$request->id) {
      return response(['error' => 'Illegal Access'], 500);
    }

    $correct = Correct::find($request->id);
    if (!$correct) return response(['error' => 'Illegal Access'], 500);

    $question = Question::find($correct->question_id);
    $questions = Question::with('corrects', 'options')->where([
      'module_id' => $question->module_id,
    ])->get();

    $correct->delete();
    return response([
      'questions' => $questions,
    ], 201);
  }
}
