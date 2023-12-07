<?php

namespace App\Http\Controllers;

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
        if($request->id) {
            $payload_correct = json_decode($request->correct, true);
            $question = Question::find($request->id);
            $correct = Correct::create([
                'content' => $payload_correct['content'],
                'solution' => $payload_correct['solution'],
                'module_id' => $question->module,
                'subject_id' => $question->subject,
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
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function update(Request $request) {
        if($request->id) {
            $payload_correct = json_decode($request->correct, true);

            $correct = Correct::find($request->id);
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
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function delete(Request $request ) { 
        if($request->id) {
            $correct = Correct::find($request->id);
            $correct->delete();

            $question = Question::find($correct->question_id);
            $questions = Question::with('corrects', 'options')->where([
                'module_id' => $question->module_id,
            ])->get();
            return response([
                'questions' => $questions,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
