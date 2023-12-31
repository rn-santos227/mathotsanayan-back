<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Grade;
use App\Models\Module;
use App\Models\Question;
use App\Models\Result;
use App\Models\Student;

class Answer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content',
        'timer',
        'attempts',
        'progress_id',
        'student_id',
        'result_id',
        'module_id',
        'question_id',
    ];

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function result() {
        return $this->belongsTo(Result::class, 'result_id', 'id');
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function grade()
    {
        return $this->hasOne(Grade::class);
    }
}
