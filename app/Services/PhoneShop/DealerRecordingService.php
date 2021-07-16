<?php

namespace App\Services\PhoneShop;

use App\Helpers\WithFileUpload;
use App\Jobs\RemoveFileAtServer;
use App\Jobs\TransferFilesToS3;
use App\Jobs\UpdateDealerRecordingStorageRecord;
use App\Jobs\UpdatePhoneShopRecordingsStorageRecord;
use App\Models\DealerRecording;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DealerRecordingService
{
    use WithFileUpload;

    public function store($phoneShopId, $audioFiles)
    {
        $audioFiles->each(function ($audioFile) use ($phoneShopId) {
            $path = "phone_shops/{$phoneShopId}/dealer-recordings";
            $fileName = $audioFile->store($path, 'local', 'public');
            $path = $fileName;
            $fileName = Str::afterLast($fileName, '/');
            $dealerRecording = DealerRecording::create([
                'phone_shop_id' => $phoneShopId,
                'label' => $audioFile->getClientOriginalName(),
                'value' => $fileName,
                'storage_disk' => 'local',
            ]);

            TransferFilesToS3::withChain([
                new RemoveFileAtServer($path),
                new UpdateDealerRecordingStorageRecord($dealerRecording->id, 's3'),
            ])->dispatch($path, $fileName);
        });
    }
}
