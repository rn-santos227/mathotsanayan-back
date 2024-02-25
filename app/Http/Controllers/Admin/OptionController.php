<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\Question;
use App\Models\Option;

use Illuminate\Http\Request;

class OptionController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function create(Request $request) {
    if(!$request->id) return response(['error' => 'Illegal Access',], 500); 

    $question = Question::find($request->id);
    if(!$question) return response(['error' => 'Illegal Access',], 500); 
    $payload_option = json_decode($request->option, true);

    $option = Option::create([
      'content' => $payload_option['content'],
      'module_id' => $question->module_id,
      'subject_id' => $question->subject_id,
      'question_id' => $question->id,
    ]);

    $file = $request->file('option_file');
    if(isset($file)) {
      $file_url = '/options/option-'.$option->id.'/option-'.$option->id.'.'.$file->getClientOriginalExtension();

      if (Storage::disk('minio')->exists($file_url)) {
        Storage::disk('minio')->delete($file_url);
      }
      
      $option->update(['file' => $file_url]);
      Storage::disk('minio')->put($file_url, file_get_contents($file));
    }

    $question->load('corrects', 'options');

    return response([
      'question' => $question,
    ], 201);
  }

  public function update(Request $request) {
    if(!$request->id) return response(['error' => 'Illegal Access',], 500); 
    $payload_option = json_decode($request->option, true);
    $option = Option::find($request->id);

    if(!$option) return response(['error' => 'Illegal Access',], 500); 
    $option->update([
      'content' => $payload_option['content'],
    ]);

    $file = $request->file('option_file');
    if(isset($file)) {
      $file_url = '/options/option-'.$option->id.'/option-'.$option->id.'.'.$file->getClientOriginalExtension();

      if (Storage::disk('minio')->exists($file_url)) {
        Storage::disk('minio')->delete($file_url);
      }
      
      $option->update(['file' => $file_url]);
      Storage::disk('minio')->put($file_url, file_get_contents($file));
    }

    $question = Question::find($option->question_id);
    $question->load('corrects', 'options');

    return response([
      'question' => $question,
    ], 201);
  }

  public function delete(Request $request ) { 
    if(!$request->id) return response(['error' => 'Illegal Access',], 500); 
    $option = Option::find($request->id);
    if(!$option) return response(['error' => 'Illegal Access',], 500); 

    $question = Question::find($option->question_id);
    $question->load('corrects', 'options');

    $option->delete();
    return response([
      'question' => $question,
    ], 201);
  }
}
