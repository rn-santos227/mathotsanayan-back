<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\School;
use App\Models\Teacher;

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
        return $this->belongsTo(School::class, 'teacher_id', 'id');
    }
}
