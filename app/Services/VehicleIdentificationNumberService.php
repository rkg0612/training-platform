<?php

namespace App\Services;

use GuzzleHttp\Client;

class VehicleIdentificationNumberService
{
    private function url($vin)
    {
        return "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValuesExtended/{$vin}?format=json";
    }

    public function get($vin)
    {
        $client = new Client();
        $client = $client->get($this->url($vin));

        return $this->filter(json_decode($client->getBody()));
    }

    private function filter($response)
    {
        $result = collect($response->Results)->first();
        if ($result->Make && $result->Model) {
            return response()->json([
                'make' => $result->Make,
                'model' => $result->Model,
            ]);
        }
        abort(404);
    }
}
