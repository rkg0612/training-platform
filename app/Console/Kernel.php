<?php

namespace App\Console;

use App\Console\Commands\DealerSidebarSchedule;
use App\Console\Commands\FilesFromImgurToS3;
use App\Console\Commands\LMSAutomotiveWeeklyReportUsage;
use App\Console\Commands\LMSDailyUsageReport;
use App\Console\Commands\LMSNonAutomotiveWeeklyReportUsage;
use App\Console\Commands\LMSVideoDayNotification;
use App\Console\Commands\LMSWeeklyReportUsage;
use App\Console\Commands\PurgeDuplicates;
use App\Console\Commands\SendLmsNoticeOfExpirationEmail;
use App\Console\Commands\SetExistingVideosToPlayed;
use App\Services\LMS\Mailers\LMSAssignedExpirationService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    const STANDARD_TZ = 'America/New_York';
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        LMSDailyUsageReport::class,
        LMSNonAutomotiveWeeklyReportUsage::class,
        FilesFromImgurToS3::class,
        LMSAutomotiveWeeklyReportUsage::class,
        SendLmsNoticeOfExpirationEmail::class,
        PurgeDuplicates::class,
        SetExistingVideosToPlayed::class,
        LMSVideoDayNotification::class,
        DealerSidebarSchedule::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('lms:expiration')
            ->timezone(self::STANDARD_TZ)
            ->daily();

//        $schedule->command('lms:daily-usage --user=kyle --user=gjacob --user=jordan')
//            ->timezone(self::STANDARD_TZ)
//            ->daily();

        $schedule->command('lms:daily-usage --user=gjacob --user=jordan')
            ->timezone(self::STANDARD_TZ)
            ->daily();

        $schedule->command('lms:automotive-weekly-usage')
            ->timezone(self::STANDARD_TZ)
            ->weeklyOn('5');

        $schedule->command('lms:non-automotive-weekly-usage')
            ->timezone(self::STANDARD_TZ)
            ->weeklyOn('5');

        $schedule->command('lms:video_notification')
            ->timezone(self::STANDARD_TZ)
            ->daily();

        $schedule->command('dealer:sidebar')
            ->timezone(self::STANDARD_TZ)
            ->dailyAt('00:02');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
