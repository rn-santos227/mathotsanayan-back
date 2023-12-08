<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;
class Course extends Model
{
    use Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'abbreviation',
        'description',
    ];
}
