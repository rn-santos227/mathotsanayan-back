<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Module;

class Course extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
    ];

    public function modules() {
        return $this->hasMany(Module::class);
    }
}
