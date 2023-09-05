<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Module;
use App\Models\Question;
use App\Models\Result;
use App\Models\Student;

class Answer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'result_id',
        'module_id',
        'question_id',
        'content',
    ];
}
