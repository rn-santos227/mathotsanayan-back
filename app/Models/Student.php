<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\School;
use App\Models\Section;
use App\Models\User;

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
        'section_id',
        'school_id',
    ];

    protected $hidden = [
        'user_id',
    ];

    public function school() {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
