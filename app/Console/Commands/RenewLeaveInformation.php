<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RenewLeaveInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renew-leave-information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yıllık izinleri günceller.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $now = Carbon::now();

        $users = User::query()->with('leaveInformation')->withWhereHas('information', function ($query) use ($now) {
            $query->whereMonth('position_started_at', $now->month)
                ->whereDay('position_started_at', $now->day);
        })->get();

        foreach ($users as $user) {
            $serviceYears = $now->year - Carbon::parse($user->information->position_started_at)->year;

            if ($serviceYears >= 0 && $serviceYears < 1) {
                $entitlement = 0;
            } else if ($serviceYears < 5) {
                $entitlement = 14;
            } elseif ($serviceYears < 15) {
                $entitlement = 20;
            } else {
                $entitlement = 26;
            }

            $user->leaveInformation()->update([
                'entitlement' => $user->leaveInformation->entitlement + $entitlement,
                'used_days' => $user->leaveInformation->used_days,
                'carryover_days' => $user->leaveInformation->remaining_days,
            ]);

        }
        return true;
    }
}
