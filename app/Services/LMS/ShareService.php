<?php

namespace App\Services\LMS;

use App\Models\AssignedModule;
use App\Models\Module;
use App\Models\User;
use App\Models\UserUnit;
use App\Notifications\ShareUnitNotification;
use Illuminate\Support\Facades\Notification;

class ShareService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function shareUnit($request)
    {
        $share_all = $request->share_all;
        $user_ids = $request->user_ids;
        $unit_id = $request->unit_id;

        if (empty($user_ids) && ! $share_all) {
            abort(404);
        }

        $users = User::with('roles');

        if (! empty($user_ids)) {
            $users->whereIn('id', $user_ids);
        }

        $users = $users->get();

        foreach ($users as $i => $usr) {
            if ($usr->id == $this->user->id) {
                continue;
            }

            $unit = UserUnit::where('unit_id', $unit_id)
                ->where('user_id', $usr->id)
                ->first();

            if (! $unit) {
                $unit = new UserUnit;
                $unit->user_id = $usr->id;
                $unit->unit_id = $unit_id;
            }

            $unit->shared_by = $this->user->id;
            $unit->save();
        }

        Notification::send(
            $users,
            (new ShareUnitNotification('/client/lms/unit/'.$unit_id, 'A unit was shared to you by '.auth()->user()->name))
        );

        return 'success';
    }

    public function shareModule($request)
    {
        $share_all = $request->share_all;
        $user_ids = $request->user_ids;
        $module_id = $request->module_id;

        $module = Module::find($module_id);

        if (empty($module)) {
            abort(404);
        }

        if (empty($user_ids) && ! $share_all) {
            abort(404);
        }

        $users = User::with('roles')
            ->where('id', '!=', $this->user->id)
            ->where('dealer_id', $this->user->dealer_id);

        if (! empty($users_ids)) {
            $users->whereIn('id', $user_ids);
        }

        if ($this->user->specific_dealer_id) {
            $users = $users->where('specific_dealer_id', $this->user->specific_dealer_id);
        }

        $users = $users->get();

        foreach ($users as $i => $usr) {
            if ($usr->id == $this->user->id) {
                continue;
            }

            $assigned_module = AssignedModule::where('module_id', $module->id)
                ->where('assignee_id', $usr->id)
                ->first();

            if (! $assigned_module) {
                $assigned_module = new AssignedModule;
                $assigned_module->user_id = $this->user->id;
                $assigned_module->assignee_id = $usr->id;
                $assigned_module->module_id = $module->id;
            }

            $assigned_module->shared_by = $this->user->id;
            $assigned_module->save();
        }

        Notification::send(
            $users,
            (new ShareUnitNotification('/client/lms/module/'.$module->build->id, 'A module was shared to you by '.auth()->user()->name))
        );

        return 'success';
    }
}
