<?php

namespace App\Jobs;

use App\Services\Twilio\OutgoingSMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VideoDayNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user_number;
    protected $outgoingSMSService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_number, OutgoingSMSService $outgoingSMSService)
    {
        $this->user_number = $user_number;
        $this->outgoingSMSService = $outgoingSMSService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request['from'] = config('twilio.twilio_from');
        $request['to'] = $this->user_number;
        $request['body'] = config('twilio.video_day_body');

        return $this->outgoingSMSService->send($request);
    }
}
