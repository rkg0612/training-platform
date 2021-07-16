<?php

namespace App\Helpers;

use App\Models\Dealer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRegister
{
    public function register(User $user, array $roles): void
    {
        if (! $this->isUserExisting($user->email)) {
            $v1UserId = $this->createRecord($user);
            $this->attachToDealer($user->id, $user->dealer_id);
            $this->attachRoles($v1UserId, $roles);
        }
    }

    public function attachRoles($userId, array $roles)
    {
        foreach ($roles as $role) {
            // we need to reverse this to according to v1 roles
            $name = Role::find($role)->name;
            if ($name === Role::SUPER_ADMINISTRATOR) {
                DB::connection('v1')->table('role_user')
                    ->insert([
                        'user_id' => $userId,
                        'role_id' => 5,
                    ]);
            }
            if ($name === ROLE::ACCOUNT_MANAGER) {
                DB::connection('v1')->table('role_user')
                    ->insert([
                        'user_id' => $userId,
                        'role_id' => 7,
                    ]);
            }
            if ($name === ROLE::SALESPERSON) {
                DB::connection('v1')->table('role_user')
                    ->insert([
                        'user_id' => $userId,
                        'role_id' => 9,
                    ]);
            }
            if ($name === ROLE::SECRET_SHOPPER) {
                DB::connection('v1')->table('role_user')
                    ->insert([
                        'user_id' => $userId,
                        'role_id' => 10,
                    ]);
            }
        }
    }

    public function createRecord($user)
    {
        DB::connection('v1')
            ->table('users')
            ->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
            ]);

        return DB::connection('v1')->table('users')
            ->where('email', $user->email)
            ->first()
            ->id;
    }

    public function attachToDealer($userId, $dealerId)
    {
        $dealer = Dealer::find($dealerId);
        if (DB::connection('v1')
            ->table('dealers')
            ->where(['name' => $dealer->name, 'website' => $dealer->website])
            ->exists()) {
            return DB::connection('v1')->table('dealers')
                ->where('name', $dealer->name)
                ->first()
                ->id;
        }

        // create and attach the options
        $v1Dealer = DB::connection('v1')
            ->table('dealers')
            ->insert([
                'name' => $dealer->name,
                'website' => $dealer->website,
                'address' => $dealerId->address,
                'logo_image' => $dealer->options->where('name', 'logo_image')->exists() ? $dealer->options->where('name', 'logo_image')->first()->value : '',
                'background_image' => $dealer->options->where('name', 'background_image')->exists() ? $dealer->options->where('name', 'background_image')->first()->value : '',
            ]);

        DB::connection('v2')
            ->table('dealer_user')
            ->insert([
                'user_id' => $userId,
                'dealer_id' => $dealerId,
            ]);

        $this->attachSettings($dealer->id, $v1Dealer->id);
    }

    public function attachSettings($v2DealerId, $v1DealerId)
    {
        $dealer = Dealer::find($v2DealerId);

        $settings = $dealer->options;

        $options = [];

        $settings->each(function ($setting) use (&$options) {
            return  [
                $options[$setting->name] = $setting->value,
            ];
        });

        DB::connection('v1')->table('dealers')
            ->where('id', $v1DealerId)
            ->update([
                'settings' => json_encode($options),
            ]);
    }

    public function isUserExisting($email)
    {
        return DB::connection('v1')
            ->table('users')
            ->where([
                'email' => $email,
            ])
            ->exists();
    }
}
