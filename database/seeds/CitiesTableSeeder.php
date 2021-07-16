<?php

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = File::get('database/seeds/json/cities.json');
        $states = json_decode($cities);
        foreach ($states as $state => $cities) {
            $state = State::where('name', '=', $state)->get();
            foreach ($cities as $city) {
                City::create([
                    'state_id' => $state->first()->id,
                    'value'    => $city,
                ]);
            }
        }
    }
}
