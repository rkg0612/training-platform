<?php

namespace App\Console\Commands;

use App\Services\LMS\UsageReport\DealerWeeklyAutomotiveUsageReportService;
use App\Services\LMS\UsageReport\DealerWeeklyNonAutomotiveUsageReportServiceService;
use App\Services\LMS\UsageReport\SpecificDealerWeeklyUsageReportServiceService;
use Illuminate\Console\Command;

class LMSAutomotiveWeeklyReportUsage extends Command
{
    protected $signature = 'lms:automotive-weekly-usage';

    protected $description = 'Send out an email to managers...';

    private $dealerWeeklyAutomotiveUsageReportService;

    private $specificDealerWeeklyUsageReport;

    public function __construct(SpecificDealerWeeklyUsageReportServiceService $specificDealerWeeklyUsageReport, DealerWeeklyAutomotiveUsageReportService $dealerWeeklyAutomotiveUsageReportService)
    {
        parent::__construct();
        $this->dealerWeeklyAutomotiveUsageReportService = $dealerWeeklyAutomotiveUsageReportService;
        $this->specificDealerWeeklyUsageReport = $specificDealerWeeklyUsageReport;
    }

    public function handle()
    {
        $this->info('Sending out an email for account managers');
        $this->specificDealerWeeklyUsageReport->sendReport(['DJohnson@hudsonauto.com']);
        $this->dealerWeeklyAutomotiveUsageReportService->sendReport(['DJohnson@hudsonauto.com']);
        $this->info('Sent successfully!');
    }
}
