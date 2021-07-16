<?php

namespace App\Services\PhoneShop\Client;

use App\Helpers\UserAcessControlChecker;
use App\Http\Resources\Client\PhoneShopCollection;
use App\Models\PhoneShop;
use App\Models\SpecificDealer;

class PhoneShopService
{
    use UserAcessControlChecker;

    public function index($request)
    {
        $phoneShop = PhoneShop::query()
            ->with([
                'dealer', 'specificDealer',
            ])
            ->select([
                'id', 'dealer_id', 'specific_dealer_id', 'shop_type', 'start_date',
            ])
            ->when($this->isSpecificDealerManager(), function ($query) {
                return $query->where('dealer_id', auth()->user()->dealer_id)
                    ->where('specific_dealer_id', auth()->user()->specific_dealer_id);
            })
            ->when($this->isAccountManager(), function ($query) {
                $specificDealerIds = SpecificDealer::query()
                    ->where('dealer_id', auth()->user()->dealer_id)
                    ->pluck('id')
                    ->toArray();

                return $query->where('dealer_id', auth()->user()->dealer_id)
                    ->whereIn('specific_dealer_id', $specificDealerIds);
            })
            ->when($this->isSalesperson(), function ($query) {
                $query = $query->where('dealer_id', auth()->user()->dealer_id);

                if (auth()->user()->specific_dealer_id) {
                    $query = $query->where('specific_dealer_id', auth()->user()->specific_dealer_id);
                }

                return $query;
            })
            ->when($request->search, function ($query, $search) {
                if ($this->isAccountManager()) {
                    return $query->whereHas('specificDealer', function ($query) use ($search) {
                        return $query->orWhere('name', 'LIKE', "%$search%")
                                ->where('dealer_id', auth()->user()->dealer_id);
                    })->orWhere('start_date', 'LIKE', "%$search%")
                        ->where('dealer_id', auth()->user()->dealer_id);
                }

                if ($this->isSpecificDealerManager()) {
                    return $query->where('start_date', 'LIKE', "%$search%")
                        ->where('dealer_id', auth()->user()->dealer_id)
                        ->where('specific_dealer_id', auth()->user()->specific_dealer_id);
                }

                if ($this->isSalesperson()) {
                    if (auth()->user()->specific_dealer_id) {
                        $query = $query
                            ->where('dealer_id', auth()->user()->dealer_id)
                            ->where('specific_dealer_id', auth()->user()->specific_dealer_id)
                            ->where('start_date', 'LIKE', "%$search%");
                    }

                    return $query->where('start_date', 'LIKE', "%$search%")
                        ->where('dealer_id', auth()->user()->dealer_id);
                }

                if ($this->isSuperAdministrator() || $this->isSecretShopper()) {
                    return $query->whereHas('dealer', function ($query) use ($search) {
                        return $query->where('name', 'LIKE', "%$search%");
                    })->orWhereHas('specificDealer', function ($query) use ($search) {
                        return $query->where('name', 'LIKE', "%$search%");
                    })
                        ->orWhere('start_date', 'LIKE', "%$search%");
                }

                return $query;
            })
            ->when($request->sortBy, function ($query, $sortBy) use ($request) {
                $sortDesc = $request->sortDesc === 'true';

                if ($sortBy === 'company') {
                    return $query->orderByDealerName($sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'specific_dealer') {
                    return $query->orderBySpecificDealerName($sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'start_date') {
                    return $query->orderBy('start_date', $sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'shop_type') {
                    return $query->orderBy('shop_type', $sortDesc ? 'desc' : 'asc');
                }

                return $query->orderByDesc('start_date');
            })
            ->when(! isset($request->sortBy), function ($query) {
                return $query->orderByDesc('start_date');
            })
            ->paginate(10);

        return new PhoneShopCollection($phoneShop);
    }
}
