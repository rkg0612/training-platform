<?php

namespace App\Http\Controllers;

use App\Services\UserProfilePictureService;
use Illuminate\Http\Request;

class UserProfilePictureController extends Controller
{
    public $userProfilePictureService;

    public function __construct(UserProfilePictureService $userProfilePictureService)
    {
        $this->userProfilePictureService = $userProfilePictureService;
    }

    public function show($id)
    {
        return $this->userProfilePictureService->show($id);
    }

    public function update($id, Request $request)
    {
        return $this->userProfilePictureService->update($id, $request);
    }
}
