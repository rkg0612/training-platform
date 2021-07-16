<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Notifications\EventCalendar;
use App\Services\Notification\GetUsersList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class EventService
{
    private $event;
    private $user;
    private $getUsersList;

    public function __construct(Event $event, GetUsersList $getUsersList)
    {
        $this->event = $event;
        $this->user = auth()->user();
        $this->getUsersList = $getUsersList;
    }

    public function index($request)
    {
        $start = Carbon::now()->year($request->year)->month($request->month)->startOfMonth();
        $end = Carbon::now()->year($request->year)->month($request->month)->endOfMonth();

        $userIds = $this->getUserIds();
        $events = Event::where('start_at', '>=', $start)
            ->where('end_at', '<=', $end)
            ->whereIn('user_id', $userIds)
            ->get();

        return $this->formatEvents($events);
    }

    public function checkIfAdmin()
    {
        if ($this->user->roles->contains('name', Role::SUPER_ADMINISTRATOR) ||
            $this->user->roles->contains('name', Role::SECRET_SHOPPER)) {
            return true;
        }

        return false;
    }

    public function store($request)
    {
        $event = new Event;
        $event->user_id = $this->user->id;
        $event->name = $request['name'];
        $event->url = $request['url'];
        $event->start_at = $request['start_at'];
        $event->end_at = $request['end_at'];
        $event->color = $request['color'];

        $event->save();

        $result = $this->formatEvent($event);

        return response($result);
    }

    public function formatEvents($events)
    {
        return $events->map(function ($event) {
            return [
                'id'    => $event->id,
                'name'  => $event->name,
                'start' => $event->start_at->toDateTimeString(),
                'end'   => $event->end_at->toDateTimeString(),
                'color' => $event->color,
                'link'  => $event->url,
                'user_id'  => $event->user_id,
            ];
        })->toArray();
    }

    public function update($request)
    {
        $model = $this->event->find($request->id);

        if (empty($model)) {
            abort(404);
        }

        if ($model->user_id !== $this->user->id) {
            abort(403);
        }

        $model->update($request->all());
        $result = $this->formatEvent($model);

        return response()->json($result);
    }

    private function formatEvent($model)
    {
        $result = $model->toArray();
        $result['start'] = $result['start_at'];
        $result['end'] = $result['end_at'];

        unset($result['start_at']);
        unset($result['end_at']);

        return $result;
    }

    private function getUserIds()
    {
        $superAdminUserIds = User::whereHas('roles', function ($query) {
            $query->where('name', Role::SUPER_ADMINISTRATOR);
        })
            ->get()
            ->pluck('id');

        $acctMngrIds = User::whereHas('roles', function ($query) {
            $query->where('name', Role::ACCOUNT_MANAGER);
        })
            ->where('dealer_id', $this->user->dealer_id)
            ->get()
            ->pluck('id');

        $dealerUserIds = User::where(function ($query) use ($acctMngrIds) {
            if (! is_null($this->user->specific_dealer_id)) {
                return $query->where('specific_dealer_id', $this->user->specific_dealer_id)
                    ->whereIn('id', $acctMngrIds);
            }

            return $query->where('dealer_id', $this->user->dealer_id);
        })
            ->get()
            ->pluck('id');

        return $superAdminUserIds
            ->merge($dealerUserIds)
            ->unique()
            ->toArray();
    }
}
