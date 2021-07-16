<?php

namespace App\Services;

use App\Helpers\WithFileUpload;
use App\Models\User;

class UserProfileService
{
    use WithFileUpload;

    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index()
    {
        return User::find($this->user->id);
    }

    public function update($request)
    {
        $user = User::find($this->user->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->job_title = $request->job_title;
        $user->sms_notification = $request->sms_notification == 'true' ? true : false;
        $user->mail_subscription = $request->mail_subscription == 'true' ? true : false;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return $user;
    }
}
