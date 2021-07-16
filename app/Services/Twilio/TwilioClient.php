<?php

namespace App\Services\Twilio;

use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class TwilioClient
{
    public $client;

    public function __construct()
    {
        try {
            $this->client = new Client(
                config('twilio.sid'),
                config('twilio.token')
            );
        } catch (ConfigurationException $e) {
            Log::critical('Twilio Credentials is not valid'.$e->getMessage());
        }
    }
}
