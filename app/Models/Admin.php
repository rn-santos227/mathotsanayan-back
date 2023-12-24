<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Admin extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'user_id',
        'email',
        'contact_number'
    ];

    protected $hidden = [
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function owber()
    {
        return $this->morphOne(User::class, 'owner');
    }
}
