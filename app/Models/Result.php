<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Answer;
use App\Models\Module;
use App\Models\Progress;
use App\Models\Student;

class Result extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'completed',
        'invalidate',
        'timer',
        'total_score',
        'items',
        'module_id',
        'progress_id',
        'student_id',
    ];

    protected $hidden = [
        'timer',
        'completed',
        'total_score',
        'grade',
        'invalidate'
    ];

    protected $appends = [
        'grade'
    ];

    public function getGradeAttribute() {
        if($this->items != 0) {
            $grade = ($this->total_score / $this->items) * 100;
        } else {
            $grade = 0;
        }

        return $grade;
    }

    public function answers() {
        return $this->hasMany(Answer::class)->withTrashed(); 
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function progress() {
        return $this->belongsTo(Progress::class, 'progress_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
