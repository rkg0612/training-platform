<?php

use App\Models\Dealer;
use App\Models\RoleUser;
use App\Models\SpecificDealer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'              => 'Administrator',
            'email'             => 'admin@example.com',
            'password'          => bcrypt('password'),
            'email_verified_at' => now(),
            'status' => 'ACTIVE',
            'remember_token'    => Str::random(10),
        ]);

        RoleUser::create([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        $dealerId = Dealer::pluck('id')->random();
        // Create a list of user for roles: acct mngr, sdm, sss, salesperson
        factory('App\Models\User', 2)->create([
            'dealer_id' => $dealerId,
            'specific_dealer_id' => SpecificDealer::where('dealer_id', $dealerId)->pluck('id')->random(),
        ])->each(
            function ($user) {
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 2,
                ]);
            }
        );
        factory('App\Models\User', 2)->create([
            'dealer_id' => Dealer::pluck('id')->random(),
        ])->each(
            function ($user) {
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 3,
                ]);
            }
        );
        factory('App\Models\User', 2)->create([
            'dealer_id' => Dealer::pluck('id')->random(),
        ])->each(
            function ($user) {
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 4,
                ]);
            }
        );
        factory('App\Models\User', 2)->create()->each(
            function ($user) {
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 5,
                ]);
            }
        );

        factory('App\Models\User', 10)->create([
            'dealer_id' => Dealer::pluck('id')->random(),
        ])->each(function ($user) {
            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 5,
            ]);
        });
    }
}
