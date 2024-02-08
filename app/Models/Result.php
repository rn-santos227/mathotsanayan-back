<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Answer;
use App\Models\Audit;
use App\Models\Module;
use App\Models\Progress;
use App\Models\Student;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Result extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'completed',
        'invalidate',
        'timer',
        'total_score',
        'items',
        'module_id',
        'progress_id',
        'student_id',
    ];

    protected $hidden = [
        'timer',
        'completed',
        'total_score',
        'grade',
        'invalidate'
    ];

    protected $appends = [
        'grade'
    ];

    public static function recordExam($module) {
        $userId = Auth::id() ?? 1; 
        Audit::create([
			'user_id'    => $userId,
			'activity'   => 'take exam',
			'table'      => 'modules', 
			'content'    => json_encode($module),
			'ip_address' => Request::ip(),
			'created_at' => now(),
		]);
    }

    public function getGradeAttribute() {
        $grade = 0;
        if($this->items != 0) {
            $grade = ($this->total_score / $this->items) * 100;
        } else {
            $module = Module::find($this->module_id);
            $grade = ($this->total_score / $module->questions()->count()) * 100;
        }

        return $grade;
    }

    public function answers() {
        return $this->hasMany(Answer::class)->withTrashed(); 
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function progress() {
        return $this->belongsTo(Progress::class, 'progress_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
