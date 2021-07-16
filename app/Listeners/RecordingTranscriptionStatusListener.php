<?php

namespace App\Listeners;

use App\Models\PhoneNumberCallLog;
use Aws\TranscribeService\TranscribeServiceClient;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordingTranscriptionStatusListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $data = json_decode($event->message['Message'], true);

        if ($data['detail']['TranscriptionJobStatus'] !== 'COMPLETED') {
            return;
        }

        $jobName = $data['detail']['TranscriptionJobName'];

        $record = PhoneNumberCallLog::where('value', 'LIKE', "%{$jobName}%")
            ->first();

        if (is_null($record)) {
            return;
        }

        $this->processTranscriptionJob($record, $jobName);
    }

    private function processTranscriptionJob(PhoneNumberCallLog $record, $jobName)
    {
        $transcribeClient = new TranscribeServiceClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        $result = $transcribeClient->getTranscriptionJob([
            'TranscriptionJobName' => $jobName,
        ]);
        $uri = $result->get('TranscriptionJob')['Transcript']['TranscriptFileUri'];

        $guzzle = new Client();
        $response = $guzzle->get($uri);

        $data = json_decode($response->getBody()->getContents(), true);

        $record->transcript = $data['results']['transcripts'][0]['transcript'];
        $record->save();
    }
}
