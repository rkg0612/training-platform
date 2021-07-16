<?php

namespace App\Services\Notification;

use App\Models\User;

class NotificationService
{
    public $user;
    public $loggedInUser;

    const SALES_PERSON = 'sales-person';
    const SUPER_ADMINISTRATOR = 'super-administrator';
    const SPECIFIC_DEALER_MANAGER = 'specific-dealer-manager';
    const SECRET_SHOPPER = 'secret-shopper';
    const ACCOUNT_MANAGER = 'account-manager';

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->loggedInUser = auth()->user();
    }

    public function index($request)
    {
        if ($request['type'] === 'unread') {
            return $this->fetchUnreadNotifications();
        }
        if ($request['type'] === 'read') {
            return $this->fetchReadNotifications();
        }

        return $this->fetchAllNotifications();
    }

    public function fetchUnreadNotifications()
    {
        return $this->loggedInUser->unreadNotifications()->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function fetchReadNotifications()
    {
        return $this->loggedInUser->readNotifications()->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function fetchAllNotifications()
    {
        return $this->loggedInUser->notifications()->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function markAsRead($id)
    {
        $this->loggedInUser->notifications->find($id)->markAsRead();

        return [
            'success' => true,
        ];
    }
}
