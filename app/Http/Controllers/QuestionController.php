<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Models\Correct;
use App\Models\Option;
use App\Models\Question;

use App\Http\Requests\QuestionRequest;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function createMany(Request $request) {
        $payload_questions = json_decode($request->questions, true);
        $payload_module = json_decode($request->module, true);
        $files = $request->question_files;
        $file_ids = array();
        $file_count = 0;

        foreach($payload_questions as $question) {
            $new_question = Question::create([
                'content' => $question['content'],
                'type' => $question['type'],
                'module_id' => $payload_module['id'],
                'subject_id' => $payload_module['subject_id'],
            ]);
            
            foreach($question['options'] as $option) {
                Option::create([
                    'content' => $option['content'],
                    'module_id' => $payload_module['id'],
                    'subject_id' => $payload_module['subject_id'],
                    'question_id' => $new_question->id,
                ]);
            };

            foreach($question['corrects'] as $correct) {
                Correct::create([
                    'content' => $correct['content'],
                    'solution' => $correct['solution'],
                    'module_id' => $payload_module['id'],
                    'subject_id' => $payload_module['subject_id'],
                    'question_id' => $new_question->id,
                ]);
            };

            if($question['has_file']) {
                array_push($file_ids, $new_question->id);
            }
        }

        foreach($files as $file) {
            $id = $file_ids[$file_count];
            $file_url = 'files/question-'.$id.'/question-'.$id.'.png';
            Storage::disk('minio')->put($file_url, (string) $file);
            $file_count++;
        }

        $questions = Question::with('corrects', 'options')->where([
            'module_id' => $payload_module['id'],
        ])->get();

        return response([
            'file' => $files ,
            'questions' => $questions,
        ], 201);
    }

    public function create(QuestionRequest $request) {
        $request->validated();
        $question = Question::create([
            'content' => $request->content,
            'type' => $request->type,
            'module_id' => $request->module,
            'subject_id' => $request->subject,
        ]);

        return response([
            'question' => $question,
        ], 201);
    }

    public function update(QuestionRequest $request) {
        if($request->id) {
            $request->validated();
            $question = json_decode($request->question, true);

            $question = Question::find($request->id);
            $question->update([
                'content' => $question['content'],
                'type' => $question['type'],
            ]);

            $file = $request->file;
            $file_url = 'files/question-'.$question->id.'.png';
            
            if ($file) {
                $question->update(['file' => $file_url]);
                Storage::disk('minio')->put($file_url, (string) $file);
            }
    
            $question->load('corrects', 'options');
            return response([
                'question' => $question,
            ], 201);
        } else return response([
            'error' => 'Illegal Access',
        ], 500);
    }

    public function delete(Request $request){
        if($request->id) {
            $question = Question::find($request->id);
            Option::where([
                "question_id" => $question->id,
            ])->delete();
            Correct::where([
                "question_id" => $question->id,
            ])->delete();
            
            $question->load('corrects', 'options');
            $question->delete();
            return response([
                'question' => $question,
            ], 201);
        } 
        else return response([
            'error' => 'Illegal Access',
        ], 500); 
    }
}
