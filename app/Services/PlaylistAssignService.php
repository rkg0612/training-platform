<?php

namespace App\Services;

use App\Jobs\SMSAssignModuleJob;
use App\Mail\LMSAssignedPlaylistNotification;
use App\Models\AssignedPlaylist;
use App\Models\Group;
use App\Models\Playlist;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ShareUnitNotification;
use App\Services\Twilio\OutgoingSMSService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PlaylistAssignService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;
    protected $outgoingSMSService;

    public function __construct(OutgoingSMSService $outgoingSMSService)
    {
        $this->user = auth()->user();
        $this->outgoingSMSService = $outgoingSMSService;
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $users = User::whereHas('roles')
            ->where(function ($query) use ($currentUser) {
                if ($currentUser->roles->pluck('name')->contains(Role::SPECIFIC_DEALER_MANAGER)) {
                    $query->whereNotNull('specific_dealer_id')->where('specific_dealer_id', $currentUser->specific_dealer_id);
                }

                if ($currentUser->roles->pluck('name')->contains(Role::ACCOUNT_MANAGER)) {
                    $query->whereNotNull('dealer_id')->where('dealer_id', $currentUser->dealer_id);
                }
            })
            ->where(function ($query) use ($request) {
                if (! $request->has('filter')) {
                    return;
                }

                $query->where('name', 'like', "%{$request->input('filter')}%");
            })->get();

        return $users;
    }

    public function store($request)
    {
        $playlist = Playlist::with(['units', 'assignedPlaylist'])
            ->where('id', $request->playlist_id)
            ->first();
        $dueDate = Carbon::parse($request->due_date);
        $format_due_date = Carbon::parse($request->due_date ?: now()->toDateTimeString())->format('m/d/Y');

        if (empty($playlist)) {
            abort(404);
        }

        if (! empty($request->groups)) {
            $groups = Group::whereIn('id', $request->groups)->get();
            foreach ($groups as $group) {
                $users = $group->users;
                foreach ($users as $assignee) {
                    $check = User::find($assignee->id);

                    if (empty($check)) {
                        continue;
                    }

                    $checkExist = AssignedPlaylist::where('playlist_id', $playlist->id)
                                                  ->where('assignee_id', $assignee->id)
                                                  ->first();

                    // this checks if the assigned playlist for the user already exists
                    // if yes, then skip it
                    if (! empty($checkExist)) {
                        continue;
                    }

                    $assignedPlaylistUnit = new AssignedPlaylist;
                    $assignedPlaylistUnit->user_id = $this->user->id;
                    $assignedPlaylistUnit->assignee_id = $assignee->id;
                    $assignedPlaylistUnit->due_date = ! empty($request->due_date) ? Carbon::parse($request->due_date) : null;
                    $playlist->assignedPlaylist()->save($assignedPlaylistUnit);

                    $link = url('/').'/playlist'.'/'.$playlist->id;
                    SMSAssignModuleJob::dispatch($this->outgoingSMSService, $assignee->id, $format_due_date, $playlist->name, $link)
                        ->delay(now()->addMinutes(1));

                    $this->sendNotification($assignee->id, $request->playlist_id, $dueDate->format('m-d-Y H:i:s a'));
                }
            }
        }

        if (! empty($request->users)) {
            $users = ($request->users == 'all') ? User::where('dealer_id', $this->user->dealer_id)
                ->whereHas('roles')->get() : User::whereIn('id', $request->users)->get();

            foreach ($users as $assignee) {
                $check = User::find($assignee->id);

                if (empty($check)) {
                    continue;
                }

                $checkExist = AssignedPlaylist::where('playlist_id', $playlist->id)
                    ->where('assignee_id', $assignee->id)
                    ->first();

                // this checks if the assigned playlist for the user already exists
                // if yes, then skip it
                if (! empty($checkExist)) {
                    continue;
                }

                $assignedPlaylistUnit = new AssignedPlaylist;
                $assignedPlaylistUnit->user_id = $this->user->id;
                $assignedPlaylistUnit->assignee_id = $assignee->id;
                $assignedPlaylistUnit->due_date = ! empty($request->due_date) ? Carbon::parse($request->due_date) : null;
                $playlist->assignedPlaylist()->save($assignedPlaylistUnit);

                $link = url('/').'/playlist'.'/'.$playlist->id;
                SMSAssignModuleJob::dispatch($this->outgoingSMSService, $assignee->id, $format_due_date, $playlist->name, $link)
                    ->delay(now()->addMinutes(1));

                $this->sendNotification($assignee->id, $request->playlist_id, $dueDate->format('m-d-Y H:i:s a'));
            }
        }

        return response()->json([
            'message' => 'playlist assigned successfully!',
        ], 200);
    }

    public function sendNotification($userId, $playlistId, $dueDate): void
    {
        $_user = User::find($userId);
        $playlist = Playlist::where('id', $playlistId)->get();

        $link = '/client/lms/playlist/'.$playlistId;
        $message = 'A playlist was assigned to you by '.$this->user->name;
        $_user->sendAssignPlaylist($link, $message, $playlist, $dueDate);
    }
}
