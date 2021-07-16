<?php

namespace App\Console\Commands;

use App\Services\LMS\UsageReport\BossDailyUsageReportService;
use Illuminate\Console\Command;

class LMSDailyUsageReport extends Command
{
    protected $signature = 'lms:daily-usage {--user=*}';

    protected $description = 'Send out lms daily usage';

    private $bossDailyUsageReport;

    public function __construct(BossDailyUsageReportService $bossDailyUsageReport)
    {
        parent::__construct();
        $this->bossDailyUsageReport = $bossDailyUsageReport;
    }

    public function handle()
    {
        $this->info('Sending Usage Daily Report Mail...');
        $this->bossDailyUsageReport->sendReport($this->option('user'));
        $this->info('Usage Daily Report Mail Sent');
    }
}
