<?php

namespace App\Services\LMS\UsageReport;

use App\Exports\DailyUsageReport\DealerDailyUsageReport;
use App\Mail\SendDailyReport;
use App\Models\Role;
use App\Models\SpecificDealer;
use App\Models\User;
use App\Services\LMS\UsageReport\UsageReportService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SpecificDealerWeeklyUsageReportServiceService extends UsageReportService
{
    public function sendReport(array $ignoredUsers = [])
    {
        $specificDealers = SpecificDealer::whereHas('dealer', function ($query) {
            $query->where('lms_service', 1)
                ->where('is_automotive', 1);
        })->get();

        $specificDealers = $specificDealers->map(function ($specificDealer) use ($ignoredUsers) {
            $users = $this->mapUsers(User::whereHas('roles', function ($query) {
                $query->where('name', Role::SALESPERSON)
                    ->orWhere('name', Role::SPECIFIC_DEALER_MANAGER);
            })
                ->where('specific_dealer_id', $specificDealer->id)
                ->get());
            $specificDealerManagers = User::whereHas('roles', function ($query) {
                $query->where('name', Role::SPECIFIC_DEALER_MANAGER);
            })
                ->where('specific_dealer_id', $specificDealer->id)
                ->whereNotIn('email', $ignoredUsers)
                ->get();

            return collect([
                'id' => $specificDealer->id,
                'dealer_id' => $specificDealer->dealer_id,
                'name' => $specificDealer->name,
                'specific_dealer_managers' => $specificDealerManagers,
                'users' => $users,
            ]);
        });

        $specificDealers->each(function ($specificDealer) {
            $fileName = 'dealers/'.$specificDealer->get('dealer_id').'/weekly-usage-reports/'.$specificDealer->get('name').'-'.now()->toDateString().'.xlsx';
            Excel::store(new DealerDailyUsageReport($specificDealer->get('users'), $specificDealer->get('name')), $fileName, 's3', null, [
                'visibility' => 'public',
            ]);

            $link = Storage::disk('s3')->url($fileName);
            $specificDealer->get('specific_dealer_managers')
                ->each(function ($specificDealerAccountManager) use ($link, $fileName) {
                    Mail::to($specificDealerAccountManager->email)
                        ->send(new SendDailyReport($specificDealerAccountManager->name, $link, $fileName));
                });
        });
    }
}
