<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

use App\Models\Module;

class Subject extends Model
{
    use Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
    ];

    public function modules() {
        return $this->hasMany(Module::class);
    }
}
