<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use wapmorgan\Mp3Info\Mp3Info;

trait WithFileUpload
{
    public function saveImageAs($file, $path, $format = 'png', $disk = 'local'): string
    {
        $image = Image::make($file);
        $stream = $image->stream($format);
        $fileName = md5($image->basename).".$format";
        $path = $path.$fileName;
        Storage::disk($disk)->put($path, $stream->__toString(), 'public');

        return $fileName;
    }

    public function saveAudio($file, $path, $disk = 'local'): array
    {
        $fileName = Str::random(50).'.mp3';

        $file = $file->storeAs($path, $fileName, [
            'visibility'=> 'public',
            'disk' => $disk,
        ]);

        try {
            $mp3Info = new Mp3Info($file, true);
        } catch (\Exception $e) {
            abort(500);
        }

        return [
            'duration' => ceil($mp3Info->duration),
            'fileName' => $fileName,
        ];
    }

    public function saveRecordings($file, $fileName, $path, $disk = 'local'): array
    {
        $file->storeAs($path, $fileName, [
            'visibility'=> 'public',
            'disk' => $disk,
        ]);

        try {
            $mp3Info = new Mp3Info($file, true);
        } catch (\Exception $e) {
            abort(500);
        }

        return [
            'duration' => ceil($mp3Info->duration),
            'fileName' => $fileName,
        ];
    }

    public function saveToS3($data)
    {
        switch ($data['folder']) {
            case 'email':
                $path = $data['id'].'/email/'.$data['name'].'.'.$data['extension'];
                Storage::disk('s3')->put($path, base64_decode($data['file']), 'public');
                break;
            case 'chat':
                $path = $data['id'].'/chat/'.$data['name'].'.'.$data['extension'];
                Storage::disk('s3')->put($path, base64_decode($data['file']), 'public');
                break;
            default:
                $path = $data['id'].'/'.$data['name'].'.'.$data['extension'];
                Storage::disk('s3')->put($path, file_get_contents($data['file']), 'public');
                break;

        }
    }
}
