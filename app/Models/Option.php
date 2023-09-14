<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;
use App\Models\Module;
use App\Models\Question;

class Option extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'content',
        'correct',
        'type',
        'file',
        'module_id',
        'course_id',
        'question_id',
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
