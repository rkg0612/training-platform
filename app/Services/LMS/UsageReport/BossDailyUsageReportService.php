<?php

namespace App\Services\LMS\UsageReport;

use App\Exports\DailyUsageReport\BigBossDailyUsageReport;
use App\Mail\SendDailyReport;
use App\Models\Dealer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

        class BossDailyUsageReportService extends UsageReportService
        {
            public function sendReport(array $sendTo)
            {
                $dealers = Dealer::has('users')
            ->where('lms_service', 1)
            ->get();

                $dealers = $dealers->map(function ($dealer) {
                    $users = $this->mapUsers(User::where('dealer_id', $dealer->id)->whereHas('roles', function ($query) {
                        $query
                    ->where('name', Role::ACCOUNT_MANAGER)
                    ->orWhere('name', Role::SALESPERSON)
                    ->orWhere('name', Role::SPECIFIC_DEALER_MANAGER);
                    })
                ->get());

                    return collect([
                        'id' => $dealer->id,
                        'name' => $dealer->name,
                        'users' => $users,
                    ]);
                });

                $fileName = 'big-boss-daily-reports/LMS Daily Usage-'.now()->toDateString().'.xlsx';

                Excel::store(new BigBossDailyUsageReport($dealers), $fileName, 's3', null, [
                    'visibility' => 'public',
                ]);

                $link = Storage::disk('s3')->url($fileName);

//                if (in_array('kyle', $sendTo)) {
//                    $bigBoss = User::where('email', ['kyle@webinarinc.com'])->where('mail_subscription', true)->first();
//                    if ($bigBoss) {
//                        Mail::to($bigBoss->email)->send(new SendDailyReport($bigBoss->name, $link, $fileName));
//                    }
//                }

                if (in_array('gjacob', $sendTo)) {
                    $bigBoss = User::where('email', ['gjacob@webinarinc.com'])->where('mail_subscription', true)->first();
                    if ($bigBoss) {
                        Mail::to($bigBoss->email)->send(new SendDailyReport($bigBoss->name, $link, $fileName));
                    }
                }

                if (in_array('jordan', $sendTo)) {
                    $bigBoss = User::where('email', ['jordan@webinarinc.com'])->where('mail_subscription', true)->first();
                    if ($bigBoss) {
                        Mail::to($bigBoss->email)->send(new SendDailyReport($bigBoss->name, $link, $fileName));
                    }
                }
            }
        }
