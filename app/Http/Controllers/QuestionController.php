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

        foreach($payload_questions as $question) {
            $new_question = Question::create([
                'content' => $question->content,
                'type' => $question->type,
                'module_id' => $request->module['id'],
                'subject_id' => $request->module['subject_id'],
            ]);
            
            foreach($question['options'] as $option) {
                Option::create([
                    'content' => $option['content'],
                    'module_id' => $request->module['id'],
                    'subject_id' => $request->module['subject_id'],
                    'question_id' => $new_question->id,
                ]);
            };

            foreach($question['corrects'] as $correct) {
                Correct::create([
                    'content' => $correct['content'],
                    'module_id' => $request->module['id'],
                    'subject_id' => $request->module['subject_id'],
                    'question_id' => $new_question->id,
                ]);
            };
        }

        foreach ($request->all() as $key => $file) {
            if (str_starts_with($key, 'question_file')) {
                if (!Storage::exists('test/test.png')) {
                    Storage::disk('minio')->put('test/test.png',(string)$file);
                }
            }
        }

        $questions = Question::with('corrects', 'options', 'solutions')->where([
            'module_id' => $payload_module['id'],
        ])->get();

        return response([
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

        $file_name = "question-".$question->id."module".$request->module.".png";

        if (!Storage::exists('questions/'.$file_name)) {
            Storage::disk('minio')->put('questions/'.$file_name, (string) $question->file);
        }

        foreach($request->options as $option) {
            $new_option = Option::create([
                'content' => $option['content'],
                'module_id' => $request->module,
                'subject_id' => $request->subject,
                'question_id' => $question->id,
            ]);
            $filename_option = "option-".$new_option->id."-".$file_name;

            if (!Storage::exists('questions/question'.$question->id."/options/".$filename_option)) {
                Storage::disk('minio')->put('questions/question'.$question->id."/options/".$filename_option, (string) $option->file);
            }
        };

        foreach($request->corrects as $correct) {
            $new_correct =  Correct::create([
                'content' => $correct['content'],
                'module_id' => $request->module,
                'subject_id' => $request->subject,
                'question_id' => $question->id,
            ]);
            $file_name_correct = "correct-".$new_correct->id."-".$file_name;
            
            if (!Storage::exists('questions/question'.$question->id."/corrects/".$file_name_correct)) {
                Storage::disk('minio')->put('questions/question'.$question->id."/corrects/".$file_name_correct, (string) $correct->file);
            }
        };

        return response([
            'question' => $question,
        ], 201);
    }

    public function update(QuestionRequest $request) {
        $request->validated();
        if($request->id) {
            $question = Question::find($request->id);
            $question->update([
                'content' => $request->content,
                'type' => $request->type,
                'module_id' => $request->module,
                'subject_id' => $request->subject,
            ]);
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
            $options = Option::where([
                "question_id" => $request->id,
            ])->get();
            $options->delete();

            $corrects = Correct::where([
                "question_id" => $request->id,
            ])->get();
            $corrects->delete();
            
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
