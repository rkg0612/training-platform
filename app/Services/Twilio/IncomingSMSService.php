<?php

namespace App\Services\Twilio;

use App\Models\InternetShop;
use App\Models\PhoneNumber;
use App\Models\PhoneNumberSMSLog;
use App\Models\SmsMedia;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class IncomingSMSService extends TwilioClient
{
    use TwilioHelper;

    public $phoneNumber;
    public $smsLog;
    public $internetShop;

    public function __construct(PhoneNumber $phoneNumber, PhoneNumberSMSLog $smsLog, InternetShop $internetShop)
    {
        parent::__construct();
        $this->phoneNumber = $phoneNumber;
        $this->smsLog = $smsLog;
        $this->internetShop = $internetShop;
    }

    public function index($twilio)
    {
        try {
            $formattedNumber = $this->getFormattedNumber($twilio['To']);
            $phoneNumberRecord = $this->phoneNumber->with('sms')
                ->where('value', $twilio['To'])
                ->orWhere('formatted_value', $twilio['To'])
                ->first();

            if (null !== $phoneNumberRecord) {
                $smsRecord = $this->log([
                    'phone_number_id' => $phoneNumberRecord->id,
                    'body'            => $twilio['Body'],
                    'from'            => $this->getFormattedNumber($twilio['From']),
                    'start_at'        => Carbon::now(),
                    'to'              => $formattedNumber,
                    'sms_message_sid' => $twilio['MessageSid'],
                ]);

                if ($twilio['NumMedia'] > 0) {
                    for ($i = 0; $i < $twilio['NumMedia']; $i++) {
                        $mediaUrl = $twilio["MediaUrl$i"];
                        $mimeType = $twilio["MediaContentType$i"];
                        $fileExtension = convertMimeToExtension($mimeType);
                        $mediaSid = basename($mediaUrl);
                        $fileName = storage_path("app/{$mediaSid}.{$fileExtension}");

                        $ch = curl_init($mediaUrl);
                        $fp = fopen($fileName, 'wb');
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);

                        SmsMedia::create([
                            'sms_id' => $smsRecord->id,
                            'name' => "{$mediaSid}.{$fileExtension}",
                            'sid' => $mediaSid,
                        ]);

                        $s3Path = "phone_numbers/{$phoneNumberRecord->id}/medias";
                        Storage::disk('s3')->putFileAs(
                            $s3Path,
                            new File($fileName),
                            "{$mediaSid}",
                            'public'
                        );

                        unlink($fileName);
                    }
                }

                if ($phoneNumberRecord->sms->isEmpty()) {
                    $this->firstMessage($twilio, $phoneNumberRecord, $formattedNumber);
                }

                $shop = $this->internetShop
                    ->where('lead_phone_number', $twilio['To'])
                    ->orWhere('lead_phone_number', $twilio['To'])
                    ->first();

                if (null !== $shop) {
                    $attempts = $shop->sms_attempts;
                    $shop->sms_attempts = $attempts + 1;
                    $shop->save();
                }
            } else {
                \Log::info('--- START Incoming SMS Log ---');
                \Log::info('--- Log got triggered because: NO PHONE NUMBER RECORD');
                \Log::info('--- Phone number that received the sms(in friendly format): '.$formattedNumber);
                \Log::info('--- Phone number that received the sms(in national convention format): '.$twilio['To']);
                \Log::info('--- END Incoming SMS Log ---');
            }

            return response('', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function firstMessage($twilio, $phoneNumberRecord, $formattedNumber)
    {
        $message = $this->client->messages->create(
                $twilio['From'],
                [
                    'from' => $twilio['To'],
                    'body'=> 'YES',
                ]
            );

        $record = $this->client->messages($message->sid)->fetch();
        $this->log([
            'phone_number_id' => $phoneNumberRecord->id,
            'body'            => 'YES',
            'from'              => $formattedNumber,
            'start_at'        => $record->dateSent->setTimezone(new \DateTimeZone('America/New_York')),
            'to'            => $this->getFormattedNumber($twilio['From']),
            'sms_message_sid' => $record->sid,
        ]);
    }

    public function log($record)
    {
        return $this->smsLog->create([
            'phone_number_id' => $record['phone_number_id'],
            'value' => $record['body'],
            'start_at' => $record['start_at'],
            'from' => $record['from'],
            'to' => $record['to'],
            'sms_message_sid' => $record['sms_message_sid'],
        ]);
    }
}
