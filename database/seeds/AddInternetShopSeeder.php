<?php

use Illuminate\Database\Seeder;

class AddInternetShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Models\InternetShop', 100)->create();
    }
}
