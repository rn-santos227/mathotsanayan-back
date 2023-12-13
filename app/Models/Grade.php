<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Answer;
use App\Models\Module;
use App\Models\Question;
use App\Models\Result;
use App\Models\Student;
use App\Models\Solution;

class Grade extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'evaluation',
        'skipped',
        'student_id',
        'result_id',
        'module_id',
        'question_id',
        'correct_id',
        'answer_id',
    ];

    public function answer() {
        return $this->belongsTo(Answer::class, 'answer_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function result() {
        return $this->belongsTo(Result::class, 'result_id', 'id');
    }

    public function correct() {
        return $this->belongsTo(Solution::class, 'correct_id', 'id');
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
