<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'content',
        'solution',
        'type',
        'file',
        'module_id',
        'course_id',
        'question_id',
    ];
}
