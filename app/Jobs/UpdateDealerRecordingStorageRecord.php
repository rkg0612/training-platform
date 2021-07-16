<?php

namespace App\Jobs;

use App\Models\DealerRecording;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDealerRecordingStorageRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dealerRecordingId;
    private $disk;

    /**
     * Create a new job instance.
     *
     * @param $dealerRecordingId
     * @param $disk
     */
    public function __construct($dealerRecordingId, $disk)
    {
        $this->dealerRecordingId = $dealerRecordingId;
        $this->disk = $disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DealerRecording::find($this->dealerRecordingId)->update([
            'storage_disk' => $this->disk,
        ]);
    }
}
