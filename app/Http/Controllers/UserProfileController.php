<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileUpdate;
use App\Services\UserProfileService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    public function index()
    {
        return $this->userProfileService->index();
    }

    public function update(UserProfileUpdate $request)
    {
        return $this->userProfileService->update($request);
    }
}
