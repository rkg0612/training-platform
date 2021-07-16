<?php

namespace App\Services;

use App\Models\Dealer;
use App\Models\DealerOption;
use App\Models\Role;
use App\Models\SpecificDealer;
use Stevebauman\Purify\Facades\Purify;

class DealerService
{
    private $dealerIndexName = 'dealer';
    private $optionsIndexName = 'options';
    private $dealers;
    private $dealer;

    private function handleOptions($options)
    {
        unset($options['primary_logo']);
        unset($options['background_image']);
        unset($options['secondary_logo']);

        foreach ($options as $key => $feature) {
            if (empty($feature)) {
                continue;
            }

            $name = $key;
            $lookup = DealerOption::where('dealer_id', $this->dealer->id)
                ->where('name', $name)
                ->first();
            $type = is_bool($feature) ? 'boolean' : 'string';

            $setting = empty($lookup) ? new DealerOption : $lookup;

            if (empty($lookup)) {
                $setting->name = $name;
            }

            $setting->dealer_id = $this->dealer->id;

            if ($setting->name === 'lms_description') {
                $setting->value = Purify::clean($feature);
            } else {
                $setting->value = $feature;
            }

            $setting->type = $type;
            $setting->save();
        }
    }

    public function index()
    {
        $dealer = new Dealer();
        $currentUser = auth()->user();
        $dealers = $dealer->with(['specificDealers.competitors', 'options'])->get();
        if ($currentUser->roles->pluck('name')->contains(Role::SPECIFIC_DEALER_MANAGER)) {
            $specificDealerId = $currentUser->specific_dealer_id;
            //			$dealers = SpecificDealer::with(['dealers'])->where('id', $currentUser->specific_dealer_id)->limit(1)->get();
            $dealers = $dealer::with([
                'specificDealers' => function ($query) use ($specificDealerId) {
                    $query->where('id', $specificDealerId)->get();
                },
                'specificDealers.competitors',
                'options',
            ])->where('id', $currentUser->dealer_id)->get();
        }

        if ($currentUser->roles->pluck('name')->contains(Role::ACCOUNT_MANAGER)) {
            $dealers = $dealer::with(['specificDealers.competitors', 'options'])->where('id', $currentUser->dealer_id)->get();
        }

        return response()->json($dealers);
    }

    public function store($request)
    {
        try {
            $data = $request->{$this->dealerIndexName};
            $data['lms_service'] = (isset($data['lms_service']) && ! is_null($data['lms_service']) && 'allowed' == $data['lms_service']) ? true : false;
            $data['secretshop_service'] = (isset($data['secretshop_service']) && ! is_null($data['secretshop_service']) && 'allowed' == $data['secretshop_service']) ? true : false;
            $this->dealer = Dealer::create($data);
        } catch (\Exception $e) {
            throw $e;
        }

        $this->handleOptions($request->{$this->optionsIndexName});

        return response($this->dealer->load('options'));
    }

    public function update($request, $dealer)
    {
        try {
            $data = $request->{$this->dealerIndexName};
            $data['lms_service'] = (isset($data['lms_service']) && ! is_null($data['lms_service']) && 'allowed' == $data['lms_service']) ? true : false;
            $data['secretshop_service'] = (isset($data['secretshop_service']) && ! is_null($data['secretshop_service']) && 'allowed' == $data['secretshop_service']) ? true : false;
            $dealer->update($data);
            $this->dealer = $dealer;
        } catch (\Exception $e) {
            throw $e;
        }

        $this->handleOptions($request->{$this->optionsIndexName});

        // put it here to avoid getting old values for options

        return response($this->dealer->load('options'));
    }

    public function destroy($dealer)
    {
        try {
            $dealer->is_active = ! $dealer->is_active;
            $dealer->save();
        } catch (\Exception $e) {
            throw $e;
        }

        return response()->json([
            'message' => 'Dealer successfully deleted.',
        ]);
    }
}
