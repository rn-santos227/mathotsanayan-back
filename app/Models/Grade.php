<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

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
        'solution_id',
        'answer_id',
    ];
}
