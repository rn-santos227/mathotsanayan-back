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
        $subject = json_decode($request->subject, true);
        $question_files = $request->file('question_files');
        $option_files = $request->file('option_files');
        $correct_files = $request->file('correct_files');

        $file_questions_ids = array();
        $file_options_ids = array();
        $file_correct_ids = array();

        foreach($payload_questions as $question) {
            $new_question = Question::create([
                'content' => $question['content'],
                'type' => $question['type'],
                'module_id' => $request->module,
                'subject_id' => $subject['id'],
            ]);
            
            foreach($question['options'] as $option) {
                $option = Option::create([
                    'content' => $option['content'],
                    'module_id' => $$request->module,
                    'subject_id' => $subject['id'],
                    'question_id' => $new_question->id,
                ]);

                if($option['has_file']) {
                    array_push($file_options_ids, $option->id);
                }
            };

            foreach($question['corrects'] as $correct) {
                $correct = Correct::create([
                    'content' => $correct['content'],
                    'solution' => $correct['solution'],
                    'module_id' => $request->module,
                    'subject_id' => $subject['id'],
                    'question_id' => $new_question->id,
                ]);

                if($correct['has_file']) {
                    array_push($file_correct_ids, $correct->id);
                }
            };

            if($question['has_file']) {
                array_push($file_questions_ids, $new_question->id);
            }
        }

        $file_count = 0;
        foreach($question_files as $question_file) {
            $id = $file_questions_ids[$file_count];
            $file_url = '/questions/question-'.$id.'/'.$question_file->getClientOriginalName();;

            $question = Question::find($id);
            $question->update(['file' => $file_url]);
            Storage::disk('minio')->put($file_url, file_get_contents($question_file));
            $file_count++;
        }

        $file_count = 0;
        foreach($option_files as $option_file) {
            $id = $file_questions_ids[$file_count];
            $file_url = '/options/option-'.$id.'/'.$option_file->getClientOriginalName();

            $option = Option::find($id);
            $option->update(['file' => $file_url]);
            Storage::disk('minio')->put($file_url, file_get_contents($option_file));
            $file_count++;
        }

        $file_count = 0;
        foreach($correct_files as $correct_file) {
            $id = $file_questions_ids[$file_count];
            $file_url = '/corrects/correct-'.$id.'/'.$correct_file->getClientOriginalName();

            $correct = Correct::find($id);
            $correct->update(['file' => $file_url]);
            Storage::disk('minio')->put($file_url, file_get_contents($correct_file));
            $file_count++;
        }

        $questions = Question::with('corrects', 'options')->where([
            'module_id' => $request->module,
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

            $file = $request->file('question_file');
            $file_url = '/files/question-'.$question->id.'.png';
            
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
