<?php

namespace App\Services\Twilio;

use App\Models\InternetShop;
use App\Models\PhoneNumber;
use App\Models\PhoneNumberCallLog;
use Twilio\TwiML\VoiceResponse;

class IncomingCallService extends TwilioClient
{
    use TwilioHelper;

    public function answer($request)
    {
        /**
         * For reference
         * https://www.twilio.com/docs/voice/twiml#twilios-request-to-your-application.
         */
        $phoneNumber = PhoneNumber::with(['voiceMail'])
            ->where('value', $request->To)
            ->orWhere('formatted_value', $request->To)
            ->first();

        if (empty($phoneNumber)) {
            $sid = $this->getSid($this->client, $request->To);

            $twilio = $this->client
                ->incomingPhoneNumbers($sid)
                ->fetch();

            $phoneNumber = PhoneNumber::with(['voiceMail'])
                ->where('value', $twilio->friendlyName)
                ->orWhere('formatted_value', $twilio->friendlyName)
                ->first();
        }

        if (empty($phoneNumber)) {
            return $this->decline();
        }

        // Log attempt

        // Create log for this call
        $check = PhoneNumberCallLog::whereCallSid($request->CallSid)->first();
        $log = empty($check) ? new PhoneNumberCallLog : $check;
        $log->phone_number_id = $phoneNumber->id;
        $log->call_sid = $request->CallSid;
        $log->from = $request->From;
        $log->save();

        $this->logAttempt($phoneNumber->value, $phoneNumber->formatted_value, $phoneNumber->id);

        /**
         * For reference
         * https://www.twilio.com/docs/voice/twiml.
         */
        $response = new VoiceResponse();
        if ($phoneNumber->voiceMail->value != 'verification-code') {
            $response->pause(['length' => env('TWILIO_PAUSE_LENGTH')]);
            $response->play($phoneNumber->voiceMail->value);
        }
        $response->record([
            'recordingStatusCallbackMethod' => 'GET',
            'recordingStatusCallback' => route('twilio.record-call'),
            'recordingStatusCallbackEvent' => 'completed',
            'playBeep' => true,
            'trim' => 'do-not-trim',
        ]);

        return $response;
    }

    public function decline()
    {
        $response = new VoiceResponse();
        $response->hangup();

        return $response;
    }

    private function logAttempt($phoneNumber, $phoneNumberFormatted, $phoneNumberId)
    {
        $internetShop = InternetShop::query()
            ->where('lead_phone_number', $phoneNumber)
            ->orWhere('lead_phone_number', $phoneNumberFormatted)
            ->first();

        if (empty($internetShop)) {
            return;
        }

        if (empty($internetShop->call_first_received)) {
            $internetShop->call_first_received = now();
        }

        $currentNumberOfCallLogs = PhoneNumberCallLog::query()
            ->where('phone_number_id', $phoneNumberId)
            ->count();

        $internetShop->call_attempts = $currentNumberOfCallLogs;

        $internetShop->save();
    }
}
