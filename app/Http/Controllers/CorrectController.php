<?php

namespace App\Http\Controllers;

use App\Models\Correct;

use App\Http\Requests\CorrectRequest;
use Illuminate\Http\Request;

class CorrectController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function create(CorrectRequest $request) {
        if($request->id) {
            $request->validated();
            $payload_correct = json_decode($request->correct, true);
            $question = Question::find($id);
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

            $corrects = Correct::where([
                "question_id" => $question->id,
            ])->get();
            return response([
                'corrects' => $corrects,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function update(CorrectRequest $request) {
        if($request->id) {
            $request->validated();
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

            $corrects = Correct::where([
                "question_id" => $question->id,
            ])->get();
            return response([
                'corrects' => $corrects,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }

    public function delete(Request $request ) { 
        if($request->id) {
            $correct = Correct::find($request->id);
            $corrects = Correct::where([
                "question_id" => $correct->question_id,
            ])->get();

            $correct->delete();
            return response([
                'corrects' => $corrects,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
