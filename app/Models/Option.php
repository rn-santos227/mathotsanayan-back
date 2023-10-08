<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Module;
use App\Models\Question;

use App\Models\Subject;
class Option extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'content',
        'file',
        'module_id',
        'subject_id',
        'question_id',
    ];

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
