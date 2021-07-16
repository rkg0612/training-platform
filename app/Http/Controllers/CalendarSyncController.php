<?php

namespace App\Http\Controllers;

use App\Services\SyncCalendarService;
use Illuminate\Http\Request;

class CalendarSyncController extends Controller
{
    public $syncCalendarService;

    public function __construct(SyncCalendarService $syncCalendarService)
    {
        $this->syncCalendarService = $syncCalendarService;
    }

    public function store($id, Request $request)
    {
        return $this->syncCalendarService->generate($id, $request->type);
    }
}
