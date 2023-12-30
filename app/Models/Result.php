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
    ];

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function student() {
        return $this->belongsTo(Student::class, 'module_id', 'id');
    }

    public function progress() {
        return $this->belongsTo(Progress::class, 'progress_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
