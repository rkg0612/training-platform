<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\MarkAsCompleted\MarkAsCompletedService;
use Illuminate\Http\Request;

class MarkAsCompletedController extends Controller
{
    public $markAsCompletedService;

    public function __construct(MarkAsCompletedService $markAsCompletedService)
    {
        $this->markAsCompletedService = $markAsCompletedService;
    }

    public function store(Request $request)
    {
        return $this->markAsCompletedService->markAsCompleted($request->all());
    }

    public function show(Request $request)
    {
        return $this->markAsCompletedService->isCompleted($request);
    }
}
