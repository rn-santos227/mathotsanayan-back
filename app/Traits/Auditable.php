<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable() {
        static::created(function ($model) {
            $model->audit('created');
        });

        static::updated(function ($model) {
            $model->audit('updated');
        });

        static::deleted(function ($model) {
            $model->audit('deleted');
        });
    }

    protected function audit($activity) {
        $excludedFields = ['file']; 
        $attributesToAudit = array_diff_key($this->attributes, array_flip($excludedFields));

        Audit::create([
            'user_id'    => Auth::id(),
            'activity'   => $activity,
            'table'      => $this->getTable(),
            'content'    => json_encode($this->attributes),
            'ip_address' => Request::ip(),
        ]);
    }

    public static function logLoginAudit($user) {
       Audit::create([
            'user_id'    => $user->id,
            'activity'   => 'login',
            'table'      => 'users', 
            'content'    => json_encode($user),
            'ip_address' => Request::ip(),
            'created_at' => now(),
        ]);
    }
}