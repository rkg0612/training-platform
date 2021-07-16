<?php

namespace App\Services\Twilio;

use App\Services\Twilio\TwilioClient;
use Twilio\Rest\Client;

class ListNumberService extends TwilioClient
{
    public function show($request)
    {
        $friendlyNumbers = [];
        $areaCodes = collect($request->areaCodes);
        if (! $areaCodes->isEmpty()) {
            $areaCodes->each(function ($areaCode) use (&$friendlyNumbers) {
                $alienNumbers = collect($this->client
                    ->availablePhoneNumbers('US')
                    ->local
                    ->read([
                        'areaCode' => $areaCode,
                    ])
                );
                \Log::debug($alienNumbers);
                $friendlyNumbers[] = $alienNumbers->map(function ($number) {
                    return $number->friendlyName;
                });
            });
        }

        return response()->json(collect($friendlyNumbers)->flatten(1));
    }

//
}
