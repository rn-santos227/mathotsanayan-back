<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\Subject;
class Progress extends Model
{
    use HasFactory;
    protected $table = "progress";
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

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
