<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\ClientLmsService;
use Illuminate\Http\Request;

class ClientLmsController extends Controller
{
    public $lmsService;

    public function __construct(ClientLmsService $lmsService)
    {
        $this->lmsService = $lmsService;
    }

    public function fetchCourseLibrary()
    {
        return $this->lmsService->fetchCourseLibrary();
    }

    public function fetchCourseLibraryLazied(Request $request)
    {
        return $this->lmsService->fetchCourseLibraryLazied($request);
    }

    public function fetchCourseLibraryHome()
    {
        return $this->lmsService->fetchCourseLibraryHome();
    }

    public function fetchAssignedUnits()
    {
        return $this->lmsService->fetchAssignedUnits();
    }

    public function fetchPlaylists()
    {
        return $this->lmsService->fetchPlaylists();
    }

    public function fetchInProgressModule()
    {
        return $this->lmsService->fetchInProgressModule();
    }

    public function fetchLatestAssignedModule()
    {
        return $this->lmsService->fetchLatestAssignedModule();
    }
}
