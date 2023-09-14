<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;

class Section extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'level',
        'description',
        'teacher_id',
        'school_id',
    ];

    public function school() {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function students() {
        return $this->hasMany(Student::class);
    }
}
