<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

use App\Models\School;
use App\Models\User;

class Teacher extends Model
{
    use Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'contact_number',
        'user_id',
        'school_id',
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $appends = [
        'full_name'
    ];

    public function getFullNameAttribute() {
        $fullname = $this->last_name . ', ' . $this->first_name;
        if(!empty($this->suffix)) $fullname = $fullname.' '.$this->suffix;
        if(!empty($this->middle_name)) $fullname = $fullname.' '.strtoupper(substr($this->middle_name, 0, 1)).'.';
        return $fullname;
    }

    public function school() {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
