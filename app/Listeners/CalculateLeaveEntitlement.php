<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CalculateLeaveEntitlement
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
    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        $serviceYears = Carbon::now()->year - Carbon::parse($user->information->position_started_at)->year;

        if ($serviceYears >= 0 && $serviceYears < 1) {
            $entitlement = 0;
        } else if ($serviceYears < 5) {
            $entitlement = 14;
        } elseif ($serviceYears < 15) {
            $entitlement = 20;
        } else {
            $entitlement = 26;
        }

        $user->leaveInformation()->create([
            'entitlement' => $entitlement,
            'used_days' => 0,
        ]);

    }
}
