<?php

namespace App\Services\Twilio;

use App\Helpers\WithFileUpload;
use App\Models\PhoneNumber;
use App\Models\PhoneNumberCallLog;
use Aws\TranscribeService\TranscribeServiceClient;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Exceptions\TwilioException;

class IncomingCallRecorderService extends TwilioClient
{
    use WithFileUpload;

    public $phoneNumberCallLogs;
    public $phoneNumber;

    public function __construct(PhoneNumber $phoneNumber, PhoneNumberCallLog $phoneNumberCallLog)
    {
        parent::__construct();
        $this->phoneNumberCallLogs = $phoneNumberCallLog;
        $this->phoneNumber = $phoneNumber;
    }

    public function index($twilio)
    {
        /**
         * Please refer here for the parameters sent by twilio
         * https://stackoverflow.com/questions/43254863/save-twilio-recordings-to-database-using-recordingstatuscallback
         * https://www.twilio.com/docs/voice/api/recording.
         */
        $recording = file_get_contents("{$twilio['RecordingUrl']}.mp3");
        $fileName = storage_path("app/{$twilio['RecordingSid']}.mp3");
        file_put_contents($fileName, $recording);

        $log = PhoneNumberCallLog::where('call_sid', $twilio['CallSid'])->first();

        $logFromTwilio = $this->client->calls($twilio['CallSid'])->fetch();

        if ($log) {
            $phoneNumber = PhoneNumber::where('id', $log->phone_number_id)
                ->first();
        } else {
            $phoneNumber = $this->phoneNumber->where('value', $logFromTwilio->toFormatted)
                ->orWhere('formatted_value', $logFromTwilio->toFormatted)
                ->first();
        }

        $s3Path = "phone_numbers/{$phoneNumber->id}/calls";

        Storage::disk('s3')->putFileAs(
            $s3Path,
            new File($fileName),
            "{$twilio['RecordingSid']}.mp3",
            'public'
        );

        unlink($fileName);

        $startTime = $logFromTwilio->startTime->setTimezone(new \DateTimeZone('America/New_York'));

        $phoneNumberCallLog = empty($log) ? new PhoneNumberCallLog : PhoneNumberCallLog::find($log->id);
        $phoneNumberCallLog->phone_number_id = $phoneNumber->id;
        $phoneNumberCallLog->value = "{$twilio['RecordingSid']}.mp3";
        $phoneNumberCallLog->from = $logFromTwilio->from;
        $phoneNumberCallLog->start_at = $startTime->format('Y-m-d H:i:s');
        $phoneNumberCallLog->call_sid = $twilio['CallSid'];
        $phoneNumberCallLog->save();

        $this->transcribeRecording($s3Path.'/'."{$twilio['RecordingSid']}.mp3", $twilio['RecordingSid']);
    }

    private function transcribeRecording($path, $recordingSid)
    {
        $transcribeClient = new TranscribeServiceClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        $transcriptionResult = $transcribeClient->startTranscriptionJob([
            'LanguageCode' => 'en-US',
            'Media' => [
                'MediaFileUri' => 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com/'.$path,
            ],
            'MediaFormat' => 'mp3',
            'TranscriptionJobName' => "{$recordingSid}",
        ]);
    }
}
