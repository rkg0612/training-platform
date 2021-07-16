<?php

namespace App\Console\Commands;

use App\Jobs\VideoDayNotificationJob;
use App\Models\FeaturedVideo;
use App\Models\Role;
use App\Models\User;
use App\Services\Twilio\OutgoingSMSService;
use Illuminate\Console\Command;

class LMSVideoDayNotification extends Command
{
    protected $outgoingSMSService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lms:video_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user for video of the day based on the start date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OutgoingSMSService $outgoingSMSService)
    {
        $this->outgoingSMSService = $outgoingSMSService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $featured_video = FeaturedVideo::whereDate('start_at', now())->first();

            if ($featured_video->start_at->diffInMinutes(now()) == 1) {
                $this->getDesignatedUsers()
                ->each(function ($numbers) {
                    VideoDayNotificationJob::dispatchNow($numbers, $this->outgoingSMSService);
                });
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * get all rightful users for sending notif.
     *
     * @return void
     */
    private function getDesignatedUsers(): object
    {
        $roles = [
            Role::ACCOUNT_MANAGER,
            Role::SPECIFIC_DEALER_MANAGER,
            Role::SALESPERSON,
        ];

        $users = User::whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        })->where('sms_notification', 1)->get();

        return $users->map(function ($user) {
            return $user->phone_number;
        })->filter(function ($element) {
            return $element !== null && $element !== 'null' && strpos($element, '@') === false;
        })->unique();
    }
}
