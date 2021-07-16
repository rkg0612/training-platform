<?php

namespace App\Services\Twilio;

use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;

class SetWebHookEndpoint extends TwilioClient
{
    use TwilioHelper;

    public function set($phoneNumber)
    {
        $sid = $this->getSid($this->client, $phoneNumber);
        try {
            $this->client->incomingPhoneNumbers($sid)->update([
                'voiceUrl' => route('twilio.incoming-voice'),
                'smsUrl' => route('twilio.incoming-sms'),
            ]);
        } catch (TwilioException $e) {
            Log::critical('Twilio Error Attaching Web Hook'.$e->getMessage());
        }
    }
}
