<?php

namespace App\Services\LMS;

use App\Jobs\SMSAssignModuleJob;
use App\Mail\LMSAssignedModuleNotification;
use App\Models\AssignedModule;
use App\Models\Group;
use App\Models\Module;
use App\Models\Playlist;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnit;
use App\Notifications\ShareUnitNotification;
use App\Services\Twilio\OutgoingSMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AssignUnitsService
{
    protected $outgoingSMSService;
    private $user;

    const MOD_LINK = '/client/lms/module/';

    public function __construct(OutgoingSMSService $outgoingSMSService)
    {
        $this->user = auth()->user();
        $this->outgoingSMSService = $outgoingSMSService;
    }

    public function assignUnit($request)
    {
        $ids = $request->user_ids;
        $unit_id = $request->unit_id;
        $due_date = $request->due_date;

        if ($request->assignToAll) {
            $ids = User::where('id', '!=', $this->user->id)
                ->where('dealer_id', $this->user->dealer_id);

            if ($this->user->specific_dealer_id) {
                $ids = $ids->where('specific_dealer_id', $this->user->specific_dealer_id);
            }

            $ids = $ids->pluck('id')
                ->toArray();
        }

        foreach ($ids as $i => $id) {
            if (! UserUnit::where('unit_id', $unit_id)->where('user_id', $id)->exists()) {
                $user_unit = new UserUnit;
                $user_unit->user_id = $id;
                $user_unit->assigned_by = $this->user->id;
                $user_unit->unit_id = $unit_id;
                $user_unit->due_date = Carbon::parse($due_date);
                $user_unit->save();
            }
        }

        return response('success');
    }

    public function assignModule($request)
    {
        $ids = $request->user_ids;
        $module_id = $request->module_id;
        $due_date = Carbon::parse($request->due_date);
        $format_due_date = Carbon::parse($request->due_date)->format('m/d/Y');

        if ($request->assignToAll) {
            $ids = User::where('id', '!=', $this->user->id)
                ->where('dealer_id', $this->user->dealer_id);

            if ($this->user->specific_dealer_id) {
                $ids = $ids->where('specific_dealer_id', $this->user->specific_dealer_id);
            }

            $ids = $ids->pluck('id')
                ->toArray();
        }

        foreach ($ids as $i => $id) {
            $assign_module = AssignedModule::where('module_id', $module_id)
                ->where('assignee_id', $id);

            if (! $assign_module->exists()) {
                $assign_module = new AssignedModule;
            } else {
                $assign_module = $assign_module->get()->first();
            }

            $assign_module->assignee_id = $id;
            $assign_module->user_id = $this->user->id;
            $assign_module->module_id = $module_id;
            $assign_module->due_date = $due_date;
            $assign_module->save();

            $link = url('/').self::MOD_LINK.$assign_module->module->id;
            SMSAssignModuleJob::dispatch($this->outgoingSMSService, $id, $format_due_date, $assign_module->module->name, $link)
                ->delay(now()->addMinutes(1));

            $this->sendNotification($id, $module_id, Carbon::parse($request->due_date)->format('m-d-Y H:i:s a'));
        }

        if (! empty($request->group_ids)) {
            $groups = Group::whereIn('id', $request->group_ids)->get();
            foreach ($groups as $group) {
                $users = $group->users;
                foreach ($users as $assignee) {
                    $id = $assignee['id'];
                    $assign_module = AssignedModule::where('module_id', $module_id)
                                                   ->where('assignee_id', $id);

                    if (! $assign_module->exists()) {
                        $assign_module = new AssignedModule;
                    } else {
                        $assign_module = $assign_module->get()->first();
                    }
                    $assign_module->assignee_id = $id;
                    $assign_module->user_id = $this->user->id;
                    $assign_module->module_id = $module_id;
                    $assign_module->due_date = $due_date;
                    $assign_module->save();

                    $link = url('/').self::MOD_LINK.$assign_module->module->id;
                    SMSAssignModuleJob::dispatch($this->outgoingSMSService, $id, $format_due_date, $assign_module->module->name, $link)
                        ->delay(now()->addMinutes(1));

                    $this->sendNotification($id, $module_id, Carbon::parse($request->due_date)->format('m-d-Y H:i:s a'));
                }
            }
        }

        return response()->json([
            'message' => 'Successfully assigned',
        ], 200);
    }

    public function fetchUsersByDealer()
    {
        $users = User::where('id', '!=', $this->user->id)
            ->where('dealer_id', $this->user->dealer_id);

        if ($this->user->specific_dealer_id) {
            $users = $users->where('specific_dealer_id', $this->user->specific_dealer_id);
        }

        return $users->get();
    }

    public function sendNotification($userId, $moduleId, $dueDate): void
    {
        $_user = User::find($userId);
        $modules = Module::where('id', $moduleId)->get();

        $link = '/client/lms/module/'.$moduleId;
        $message = 'A module was assigned to you by '.$this->user->name;
        $_user->sendAssignModule($link, $message, $modules, $dueDate);
    }
}
