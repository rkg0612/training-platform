<?php

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = File::get('database/seeds/json/states.json');
        $data = json_decode($states);
        foreach ($data as $state) {
            State::create([
                'name'         => $state->name,
                'abbreviation' => $state->abbreviation,
                'area_codes'   => $state->area_codes,
            ]);
        }
    }
}
