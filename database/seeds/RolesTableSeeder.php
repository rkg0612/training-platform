<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add more roles here...
        $roles = [
            'Super Administrator',
            'Account Manager',
            'Specific Dealer Manager',
            'Secret Shopper',
            'Salesperson',
        ];
        foreach ($roles as $role) {
            Role::create([
                'friendly_name' => $role,
                'name' => \Str::slug($role),
            ]);
        }
    }
}
