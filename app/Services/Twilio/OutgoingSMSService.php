<?php

namespace App\Services\Twilio;

use App\Models\InternetShop;
use App\Models\PhoneNumber;
use App\Models\PhoneNumberSMSLog;
use Carbon\Carbon;

class OutgoingSMSService extends TwilioClient
{
    use TwilioHelper;

    public $log;
    public $phoneNumber;
    public $internetShop;

    public function __construct(PhoneNumberSMSLog $log, PhoneNumber $phoneNumber, InternetShop $internetShop)
    {
        parent::__construct();
        $this->phoneNumber = $phoneNumber;
        $this->log = $log;
        $this->internetShop = $internetShop;
    }

    public function send($request)
    {
        $message = $this->client->messages->create(
            $request['to'],
            [
                'from' => $request['from'],
                'body' => $request['body'],
            ]
        );

        $record = $this->client->messages($message->sid)->fetch();
        $this->log([
            'phone_number_id' => $this->phoneNumber->where('value', $request['from'])->first()->id,
            'value' => $request['body'],
            'start_at' => $record->dateSent->setTimezone(new \DateTimeZone('America/New_York')),
            'from' => $request['from'],
            'to' =>  $request['to'],
            'sms_message_sid' => $record->sid,
        ]);

        return $message;
    }

    public function log($record)
    {
        return $this->log->create([
            'phone_number_id' => $record['phone_number_id'],
            'value' => $record['value'],
            'start_at' => $record['start_at'],
            'from' => $record['from'],
            'to' =>  $record['to'],
            'sms_message_sid' => $record['sms_message_sid'],
        ]);
    }
}
