<?php

namespace App\Services\LMS\UsageReport;

use App\Exports\DailyUsageReport\DealerDailyUsageReport;
use App\Mail\SendDailyReport;
use App\Models\Dealer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DealerWeeklyNonAutomotiveUsageReportServiceService extends UsageReportService
{
    public function sendReport(array $ignoredUsers = [])
    {
        $dealers = Dealer::has('users')
            ->where('lms_service', 1)
            ->where('is_automotive', 0)
            ->get();

        $dealers = $dealers->map(function ($dealer) use ($ignoredUsers) {
            $users = $this->mapUsers(User::whereHas('roles', function ($query) {
                $query
                        ->where('name', Role::ACCOUNT_MANAGER)
                        ->orWhere('name', Role::SALESPERSON)
                        ->orWhere('name', Role::SPECIFIC_DEALER_MANAGER);
            })
                ->where('dealer_id', $dealer->id)
                ->get());
            $accountManagers = User::whereHas('roles', function ($query) {
                $query->where('name', Role::ACCOUNT_MANAGER);
            })
                ->where('dealer_id', $dealer->id)
                ->whereNotIn('email', $ignoredUsers)
                ->get();

            return collect([
                'id' => $dealer->id,
                'name' => $dealer->name,
                'account_managers' => $accountManagers,
                'users' => $users,
            ]);
        });

        $dealers->each(function ($dealer) {
            $fileName = 'dealers/'.$dealer->get('id').'/weekly-usage-reports/'.$dealer->get('name').'-'.now()->toDateString().'.xlsx';
            Excel::store(new DealerDailyUsageReport($dealer->get('users'), $dealer->get('name')), $fileName, 's3', null, [
                'visibility' => 'public',
            ]);

            $link = Storage::disk('s3')->url($fileName);
            $dealer->get('account_managers')->each(function ($accountManager) use ($link, $fileName) {
                Mail::to($accountManager->email)->send(new SendDailyReport($accountManager->name, $link, $fileName));
            });
        });
    }
}
