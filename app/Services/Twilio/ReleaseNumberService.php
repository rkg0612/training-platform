<?php

namespace App\Services\Twilio;

use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;

class ReleaseNumberService extends TwilioClient
{
    public function release($phoneNumber)
    {
        $twilioPhoneNumber = $this->client->incomingPhoneNumbers->read([
            'phoneNumber' => $phoneNumber,
        ]);

        try {
            return $twilioPhoneNumber[0]->delete();
        } catch (TwilioException $e) {
            Log::critical('The number cannot be released right now'.$e->getMessage());
        }
    }
}
