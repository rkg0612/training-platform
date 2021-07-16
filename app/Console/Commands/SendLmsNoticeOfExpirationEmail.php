<?php

namespace App\Console\Commands;

use App\Services\LMS\Mailers\LMSAssignedExpirationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendLmsNoticeOfExpirationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lms:expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out an email daily notifying the users of their assigned modules/playlists/units that are about to expire.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sending out mail to the users....');
        try {
            new LMSAssignedExpirationService();
        } catch (\Exception $e) {
            Log::info('Can\'t send notice of expiration');
        }
    }
}
