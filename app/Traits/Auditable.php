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
        $excludedFields = ['file','password']; 
        $attributesToAudit = array_diff_key($this->attributes, array_flip($excludedFields));

        $userId = Auth::id() ?? 1; 

        Audit::create([
            'user_id'    => $userId,
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

    public static function takeExame($module) {
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
}