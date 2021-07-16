<?php

namespace App\Services;

use App\Helpers\WithFileUpload;
use App\Jobs\TransferFilesToS3;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserProfilePictureService
{
    use WithFileUpload;

    public function show($userId)
    {
        $image = Storage::disk('s3')->url('users/'.auth()->user()->id.'/'.auth()->user()->profile_picture);

        return Image::make($image)->encode('data-url');
    }

    public function update($id, $request)
    {
        $this->checkForExistingPhoto();
        $fileName = $this->setProfilePicture($request->get('params')['picture']);
        $this->checkFileIfExistsOnS3($fileName);
        $user = User::find($id);
        $user->profile_picture = $fileName;
        $user->save();

        return $fileName;
    }

    public function setProfilePicture($picture)
    {
        $path = 'users/'.auth()->user()->id.'/';
        $fileName = $this->saveImageAs($picture, $path, 'jpg', 'local');

        TransferFilesToS3::dispatchNow($path, $fileName);

        return $fileName;
    }

    public function checkForExistingPhoto()
    {
        $fileName = User::find(auth()->user()->id)->profile_picture;
        if (Storage::disk('s3')->exists('users/'.auth()->user()->id.'/'.$fileName)) {
            return $this->removeOldPhoto($fileName);
        }

        return true;
    }

    public function removeOldPhoto($fileName)
    {
        return Storage::disk('s3')->delete('users/'.auth()->user()->id.'/'.$fileName);
    }

    public function checkFileIfExistsOnS3($fileName)
    {
        do {
            if (Storage::disk('s3')->exists('users/'.auth()->user()->id.'/'.$fileName)) {
                break;
            }
        } while (true);

        return $fileName;
    }
}
