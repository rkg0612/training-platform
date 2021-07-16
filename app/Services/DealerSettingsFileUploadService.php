<?php

namespace App\Services;

use App\Helpers\WithFileUpload;
use App\Models\DealerOption;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DealerSettingsFileUploadService
{
    use WithFileUpload;

    public function store($request)
    {
        $images = $request->allFiles();
        $dealerId = $request->dealer_id;
        $path = "dealers/{$dealerId}/options/";

        if (isset($images['background_image'])) {
            $this->updateRecord($dealerId, 'background_image', $images['background_image'], $path);
        }
        if (isset($images['logo_image'])) {
            $this->updateRecord($dealerId, 'logo_image', $images['logo_image'], $path);
        }
        if (isset($images['secondary_logo'])) {
            $this->updateRecord($dealerId, 'secondary_logo', $images['secondary_logo'], $path);
        }

        return response('success', 200);
    }

    public function checkIfRecordIsExisting($dealerId, $option)
    {
        return DealerOption::where('dealer_id', $dealerId)->where('name', $option)->exists();
    }

    public function updateRecord($dealerId, $option, $file, $path)
    {
        $fileName = $this->saveToS3($file, $path);
        if ($this->checkIfRecordIsExisting($dealerId, $option)) {
            $this->unlinkOldFile($this->getFileName($option, $dealerId), $path);

            return DealerOption::where('dealer_id', $dealerId)
                ->where('name', $option)
                ->update([
                    'value' => Storage::disk('s3')->url($path.$fileName),
                ]);
        }

        return DealerOption::create([
            'name' => $option,
            'value' => Storage::disk('s3')->url($path.$fileName),
            'type' => 'string',
        ]);
    }

    public function saveToS3($file, $path)
    {
        return $this->saveImageAs($file, $path, 'jpg', 's3');
    }

    public function getFileName($option, $dealerId)
    {
        $url = DealerOption::where('name', $option)->where('dealer_id', $dealerId)->first()->value;

        return Str::afterLast($url, '/');
    }

    public function unlinkOldFile($fileName, $path)
    {
        return Storage::disk('s3')->delete($path.$fileName);
    }
}
