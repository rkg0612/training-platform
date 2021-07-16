<?php

namespace App\Http\Controllers;

use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        return $this->notificationService->index($request->all());
    }

    public function unread(Request $request)
    {
        return $this->notificationService->fetchUnreadNotifications();
    }

    public function markAsRead(Request $request)
    {
        return $this->notificationService->markAsRead($request->id);
    }
}
