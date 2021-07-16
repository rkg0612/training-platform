<?php

namespace App\Services\PhoneShop;

use App\Helpers\WithFileUpload;
use App\Jobs\RemoveFileAtServer;
use App\Jobs\TransferFilesToS3;
use App\Jobs\UpdateCompetitorRecordingStorageRecord;
use App\Models\CompetitorRecording;
use Illuminate\Support\Str;
use function Matrix\add;

class CompetitorRecordingService
{
    use WithFileUpload;

    public function store($phoneShopId, $audioFiles)
    {
        $audioFiles->each(function ($audioFile) use ($phoneShopId) {
            $path = "/phone_shops/{$phoneShopId}/competitor-recordings/";
            $fileName = $audioFile->store($path, 'local', 'public');
            $path = $fileName;
            $fileName = Str::afterLast($fileName, '/');
            $competitorRecording = CompetitorRecording::create([
                'phone_shop_id' => $phoneShopId,
                'label' => $audioFile->getClientOriginalName(),
                'value' => $fileName,
                'storage_disk' => 'local',
            ]);

            TransferFilesToS3::withChain([
                new RemoveFileAtServer($path),
                new UpdateCompetitorRecordingStorageRecord($competitorRecording->id, 's3'),
            ])->dispatch($path, $fileName);
        });
    }
}
