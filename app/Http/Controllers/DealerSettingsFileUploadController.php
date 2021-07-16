<?php

namespace App\Http\Controllers;

use App\Services\DealerSettingsFileUploadService;
use Illuminate\Http\Request;

class DealerSettingsFileUploadController extends Controller
{
    public $dealerSettingsFileUploadService;

    public function __construct(DealerSettingsFileUploadService $dealerSettingsFileUploadService)
    {
        $this->dealerSettingsFileUploadService = $dealerSettingsFileUploadService;
    }

    public function store(Request $request)
    {
        return $this->dealerSettingsFileUploadService->store($request);
    }
}
