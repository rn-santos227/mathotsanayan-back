<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

use App\Models\Subject;
use App\Models\Module;
use App\Models\Correct;
use App\Models\Option;

class Question extends Model
{
    use Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'content',
        'type',
        'file',
        'module_id',
        'subject_id',
    ];

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
    
    public function corrects() {
        return $this->hasMany(Correct::class);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }
}
