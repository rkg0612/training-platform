<?php

namespace App\Jobs;

use App\Models\CompetitorRecording;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCompetitorRecordingStorageRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $competitorRecordingId;
    private $disk;

    /**
     * Create a new job instance.
     *
     * @param $competitorRecordingId
     * @param $disk
     */
    public function __construct($competitorRecordingId, $disk)
    {
        $this->competitorRecordingId = $competitorRecordingId;
        $this->disk = $disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CompetitorRecording::find($this->competitorRecordingId)->update([
            'storage_disk' => $this->disk,
        ]);
    }
}
