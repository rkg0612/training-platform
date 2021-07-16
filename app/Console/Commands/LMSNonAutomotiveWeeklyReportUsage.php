<?php

namespace App\Console\Commands;

use App\Services\LMS\UsageReport\DealerWeeklyNonAutomotiveUsageReportServiceService;
use App\Services\LMS\UsageReport\SpecificDealerWeeklyUsageReportServiceService;
use Illuminate\Console\Command;

class LMSNonAutomotiveWeeklyReportUsage extends Command
{
    protected $signature = 'lms:non-automotive-weekly-usage';

    protected $description = 'Send out an email to managers...';

    private $dealerWeeklyUsageReport;

    public function __construct(DealerWeeklyNonAutomotiveUsageReportServiceService $dealerWeeklyUsageReport)
    {
        parent::__construct();
        $this->dealerWeeklyUsageReport = $dealerWeeklyUsageReport;
    }

    public function handle()
    {
        $this->info('Sending weekly mail report...');
        $this->dealerWeeklyUsageReport->sendReport(['DJohnson@hudsonauto.com']);
        $this->info('Week Mail Report Sent');
    }
}
