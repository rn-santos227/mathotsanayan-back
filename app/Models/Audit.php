<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Audit extends Model
{
    use HasFactory;
    protected $table = "audits";
    protected $fillable = [
        'user_id',
        'activity',
        'table',
        'content',
        'ip_address',
    ];

    protected $hidden = [
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
