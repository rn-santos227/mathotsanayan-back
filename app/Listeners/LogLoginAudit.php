<?php

namespace App\Listeners;

use App\Events\Laravel\Sanctum\Events\AccessTokenCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Audit;
use Illuminate\Support\Facades\Request;

class LogLoginAudit
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AccessTokenCreated $event): void
    {
        $user = $event->user;

        $audit = new Audit([
            'user_id'    => $user->id,
            'activity'   => 'login',
            'table'      => 'personal_access_tokens',
            'content'    => json_encode($user),
            'ip_address' => Request::ip(),
        ]);

        $audit->save();
    }
}
