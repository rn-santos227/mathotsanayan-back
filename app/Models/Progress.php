<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;
    protected $fillable = [
        'average',
        'total_time',
        'progress',
        'skips',
        'passed',
        'failed',
        'tries',
        'student_id',
        'subject_id',
    ];
}
