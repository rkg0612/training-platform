<?php

namespace App\Services\LMS\MarkAsCompleted;

use App\Mail\ManagerCompletionMail;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnit;
use App\Services\LMS\Mailers\CertificateOfCompletionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserUnitMarkAsCompletedService
{
    public function markAsCompleted($request)
    {
        $user_unit = UserUnit::where('user_id', auth()->user()->id)
            ->where('unit_id', $request['unitId'])
            ->first();

        if (! $user_unit) {
            $user_unit = new UserUnit;
            $user_unit->user_id = auth()->user()->id;
            $user_unit->unit_id = $request['unitId'];
        }

        $user_unit->date_completed = Carbon::now();
        $user_unit->save();

        if ($user_unit->unit->module->build->progress >= 100) {
            $unit = Unit::find($request['unitId']);
            $series = $unit->series->module_series->module_build->module->name;
            $category = $unit->module->category->name;
            $mailer = new CertificateOfCompletionService();
            $mailer->send(auth()->user(), $series, $category);
        }

        return $user_unit;
    }

    private function sendToManagers($user, $series, $category)
    {
        $managers = User::with('roles')
            ->whereHas('roles', function ($query) use ($user) {
                if (! is_null($user->specific_dealer_id)) {
                    $query->where('name', 'specific-dealer-manager');
                } else {
                    $query->where('name', 'account-manager');
                }
            });

        if (! is_null($user->specific_dealer_id)) {
            $managers = $managers->where('specific_dealer_id', auth()->user()->specific_dealer_id);
        } else {
            $managers = $managers->where('dealer_id', auth()->user()->dealer_id);
        }

        $managers = $managers->get();

        if (! empty($managers)) {
            foreach ($managers as $manager) {
                Mail::to($manager->email)->send(new ManagerCompletionMail($manager->name, $user->name, $series, $category));
            }
        }
    }

    public function isCompleted($request)
    {
        return UserUnit::where('user_id', auth()->user()->id)
            ->where('unit_id', $request['unitId'])
            ->whereNotNull('date_completed')
            ->exists();
    }
}
