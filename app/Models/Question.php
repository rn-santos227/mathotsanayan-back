<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Course;
use App\Models\Module;

use App\Models\Option;
use App\Models\Solution;

class Question extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'content',
        'type',
        'file',
        'module_id',
        'course_id',
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'module_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'course_id', 'id');
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function solutions() {
        return $this->hasMany(Solution::class);
    }
}
