<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;

use App\Models\Question;

class Module extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'step',
        'course_id',
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'module_id', 'id');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
}
