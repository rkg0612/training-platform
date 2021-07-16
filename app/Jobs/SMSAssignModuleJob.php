<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Twilio\OutgoingSMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SMSAssignModuleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $outgoingSMSService;
    protected $user_id;
    protected $due_date;
    protected $mod_name;
    protected $mod_link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OutgoingSMSService $outgoingSMSService, $user_id, $due_date, $mod_name, $mod_link)
    {
        $this->outgoingSMSService = $outgoingSMSService;
        $this->user_id = $user_id;
        $this->due_date = $due_date;
        $this->mod_name = $mod_name;
        $this->mod_link = $mod_link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $user = User::where('sms_notification', true)->find($this->user_id);

            $request['from'] = config('twilio.twilio_from');
            $request['to'] = $user->phone_number;
            $request['body'] = strtr(config('twilio.assign_mod_body'), [
                'due_date'  => $this->due_date,
                'mod_name' => $this->mod_name,
                'mod_link' => $this->mod_link,
            ]);

            return $this->outgoingSMSService->send($request);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
