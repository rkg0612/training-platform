<?php

namespace App\Services\Twilio;

use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;

class BuyNumberService extends TwilioClient
{
    public function buy($number)
    {
        try {
            $number = $this->client->incomingPhoneNumbers->create([
                'phoneNumber' => $number,
            ]);

            return $number;
        } catch (TwilioException $e) {
            Log::critical('The number is not available for procurement '.$e->getMessage());
        }
    }
}
