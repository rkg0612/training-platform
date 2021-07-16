<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStatusUpdateRequest;
use App\Mail\UserGotApproved;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserStatusController extends Controller
{
    public function update(UserStatusUpdateRequest $request, User $user)
    {
        $newStatus = $request->input('status');
        try {
            $oldStatus = $user->status;
            $user->status = $newStatus;
            $user->save();

            // only send this when the old status and new status is the not the same to avoid spam.
            if ($oldStatus != $newStatus && $newStatus == User::ACTIVE) {
                Mail::to($user->email)
                   ->queue(new UserGotApproved($user));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return response($user);
    }
}
