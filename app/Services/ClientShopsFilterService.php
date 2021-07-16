<?php

namespace App\Services;

use App\Helpers\ClientShopsHelper;
use App\Models\City;
use App\Models\InternetShop;
use App\Models\LeadSource;
use App\Models\SpecificDealer;
use App\Models\State;
use App\Models\User;

class ClientShopsFilterService
{
    private $user;
    private $internetShop;
    private $clientShopsHelper;

    public function __construct(ClientShopsHelper $clientShopsHelper)
    {
        $this->user = auth()->user();
        $this->internetShop = InternetShop::query();
        $this->clientShopsHelper = $clientShopsHelper;
    }

    public function index($request)
    {
        return response()->json([
            'makeAndModel'   =>  $this->fetchMakeAndModel($request),
            'state' =>  $this->fetchState($request),
            'city' =>  $this->fetchCity($request),
            'specific_dealers' =>  $this->fetchSpecificDealers($request),
            'zip_codes' =>  $this->fetchZipCode($request),
            'sales_people'  =>  $this->fetchSalesPeople($request),
            'lead_source'   =>  $this->fetchSource($request),
            'csm'   =>  $this->user->dealer_id === 48 ? $this->fetchCSM($request) : [],
        ]);
    }

    private function fetchCSM($request = null)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);
        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());

        return $internetShop->whereNotNull('csm_name')
            ->pluck('csm_name')
            ->unique()
            ->toArray();
    }

    private function fetchSource($request = null)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);
        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());
        $ids = $internetShop->pluck('lead_source')
                ->unique()
                ->map(function ($ls) {
                    return intval($ls);
                })
                ->toArray();

        return LeadSource::whereIn('id', array_values($ids))
                ->get();
    }

    private function fetchZipCode($request = null)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);
        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());
        $result = $internetShop->whereNotNull('zipcode')
            ->pluck('zipcode')
            ->unique()
            ->toArray();

        return array_values($result);
    }

    private function fetchSalesPeople($request = null)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);
        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());
        $result = $internetShop->whereNotNull('salesperson_name')
            ->pluck('salesperson_name')
            ->unique()
            ->toArray();

        return array_values($result);
    }

    private function fetchState($request = null)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);

        return State::whereHas('internetShops', function ($query) use ($request) {
            $query->where('dealer_id', $this->user->dealer_id);
            $this->clientShopsHelper->builderQuery($query, $request->all());
        })
            ->select('id', 'name', 'abbreviation')
            ->get();
    }

    private function fetchSpecificDealers($request = null)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);

        return SpecificDealer::whereHas('internetShops', function ($query) use ($request) {
            $query->where('dealer_id', $this->user->dealer_id);
            $this->clientShopsHelper->builderQuery($query, $request->all());
        })
            ->get();
    }

    private function fetchCity($request)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);
        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());

        return City::whereIn('id', $internetShop->pluck('city_id')->toArray())
            ->get();
    }

    private function fetchMakeAndModel($request)
    {
        $is = $this->user->specific_dealer_id ? $this->internetShop->where('specific_dealer_id', $this->user->specific_dealer_id) : $this->internetShop->where('dealer_id', $this->user->dealer_id);
        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());

        $internetShop = $internetShop->whereNotNull('make')
            ->where('model', '!=', '');

        $make = $internetShop->pluck('make')
            ->unique()
            ->toArray();

        $model = $internetShop->pluck('model')
            ->unique()
            ->toArray();

        return [
            'make'  =>  array_values($make),
            'model' =>  array_values($model),
        ];
    }
}
