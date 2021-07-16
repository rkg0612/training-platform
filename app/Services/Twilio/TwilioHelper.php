<?php

namespace App\Services\Twilio;

trait TwilioHelper
{
    public function getSid($client, $phoneNumber)
    {
        return $client->incomingPhoneNumbers->read([
            'phoneNumber' => $phoneNumber,
        ])[0]->sid;
    }

    public function getFormattedNumber($value)
    {
        $phoneNumber = str_replace('+1', '', $value);
        $cleaned = preg_replace('/\D/', '', $phoneNumber);
        preg_match('/^(\d{3})(\d{3})(\d{4})$/', $cleaned, $match);

        return "(${match[1]}) ${match[2]}-${match[3]}";
    }
}
