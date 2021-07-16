<?php

namespace App\Services\Twilio;

use Carbon\Carbon;

class GetLogsService extends TwilioClient
{
    public $result;

    public function show($request)
    {
        if ($request->call) {
            $this->calls($request->number, $request->timezone);
        }

        if ($request->sms) {
            $this->sms($request->number, $request->timezone);
        }

        return response()->json($this->result);
    }

    public function sms($number, $timezone)
    {
        $messages = $this->client->messages->read([
            'to' => $number,
        ]);

        if (! empty($messages)) {
            $this->result['sms']['attempts'] = count($messages);
            $this->result['sms']['firstReceived'] = array_pop($messages)->dateSent->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s.u');
        }
    }

    public function calls($number, $timezone)
    {
        $calls = $this->client->calls->read([
            'to' => $number,
        ]);

        if (! empty($calls)) {
            $this->result['call']['attempts'] = count($calls);
//            $this->result['call']['firstReceived'] = array_pop($calls)->startTime->format('m-d-Y h:i:s A')->setTimezone(config('app.timezone'));
            $this->result['call']['firstReceived'] = array_pop($calls)->startTime->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s.u');
        }
    }
}
