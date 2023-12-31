<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

use App\Models\Subject;
use App\Models\Question;
class Module extends Model
{
    use Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'objective',
        'description',
        'direction',
        'passing',
        'step',
        'passing',
        'active',
        'subject_id',
    ];

    protected $appends = [
        'count'
    ];

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function getCountAttribute()
    {
        return $this->questions()->count();
    }
}
