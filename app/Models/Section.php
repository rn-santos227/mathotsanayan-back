<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

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
}
