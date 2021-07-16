<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRegisterRequest;
use App\Http\Requests\UserRegistration;
use App\Mail\NewUserMailForAdmins;
use App\Mail\NewUserMailForManagers;
use App\Mail\NewUserSelfSignup;
use App\Models\Dealer;
use App\Models\SpecificDealer;
use App\Models\User;
use App\Services\UserRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function store(NewUserRegisterRequest $request)
    {
        $requestDataDealerId = request('dealer')['id'];
        if (is_int($requestDataDealerId)) {
            $existingSpecificDealer = SpecificDealer::find($requestDataDealerId);
            if ($existingSpecificDealer) {
                $manager = User::find(request('manager'));
            }
        }

        // password needs to be default
        try {
            // specific dealers attachment + ung manager na seselect nila
            $user = User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('Webinarinc123')),
                'specific_dealer_id' => (isset($existingSpecificDealer) && $existingSpecificDealer) ? $existingSpecificDealer->id : null,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        Mail::to($user->email)->queue(
            new NewUserSelfSignup($user)
        );

        Mail::to(env('ADMIN_WEBINARINC_EMAIL', 'admin@webinarinc.com'))->queue(
            new NewUserMailForAdmins(
                $user,
                (isset($existingSpecificDealer) && $existingSpecificDealer) ? $existingSpecificDealer : $requestDataDealerId,
                (isset($manager) && $manager) ? $manager : null
            )
        );

        return response($user);
    }
}
