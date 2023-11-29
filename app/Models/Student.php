<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;
use App\Models\School;
use App\Models\Section;
use App\Models\User;

use App\Models\Result;
class Student extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'student_number',
        'email',
        'contact_number',
        'user_id',
        'course_id',
        'section_id',
        'school_id',
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $appends = [
        'full_name'
    ];

    public function getFullNameAttribute()
    {
        $fullname = $this->last_name . ', ' . $this->first_name;
        if(!empty($this->middle_name)) $fullname = $fullname.' '.strtoupper(substr($this->middle_name, 0, 1)).'.';
        if(!empty($this->suffix)) $fullname = $fullname.' '.$this->suffix;
        return $fullname;
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function school() {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function results() {
        return $this->hasMany(Result::class);
    }
}
