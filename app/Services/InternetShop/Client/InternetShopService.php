<?php

namespace App\Services\InternetShop\Client;

use App\Helpers\UserAcessControlChecker;
use App\Http\Resources\Client\InternetShopCollection;
use App\Models\InternetShop;
use App\Models\User;

class InternetShopService
{
    use UserAcessControlChecker;

    public function index($request)
    {
        $internetShops = InternetShop::withoutGlobalScopes()
            ->withoutTrashed()
            ->with([
                'specificDealer', 'competitor', 'dealer',
            ])
            ->when($request->search, function ($query, $search) {
                if ($this->isAccountManager()) {
                    return $query
                        ->whereHas('specificDealer', function ($query) use ($search) {
                            return $query->where('name', 'LIKE', "%$search%")
                                ->where('dealer_id', auth()->user()->dealer_id);
                        })
                        ->orWhere('make', 'LIKE', "%$search%")
                        ->orWhere('model', 'LIKE', "%$search%")
                        ->orWhere('start_at', 'LIKE', "%$search%")
                        ->orWhereHas('competitor', function ($query) use ($search) {
                            return $query->where('name', 'LIKE', "%$search%");
                        });
                }

                if ($this->isSalesperson() && empty(auth()->user()->specific_dealer_id)) {
                    return $query
                        ->whereHas('specificDealer', function ($query) use ($search) {
                            return $query->where('name', 'LIKE', "%$search%")
                                ->where('dealer_id', auth()->user()->dealer_id);
                        })
                        ->orWhere('make', 'LIKE', "%$search%")
                        ->orWhere('model', 'LIKE', "%$search%")
                        ->orWhere('start_at', 'LIKE', "%$search%")
                        ->orWhereHas('competitor', function ($query) use ($search) {
                            return $query->where('name', 'LIKE', "%$search%");
                        });
                }

                if ($this->isSuperAdministrator() || $this->isSecretShopper()) {
                    return $query->whereHas('dealer', function ($query) use ($search) {
                        return $query->where('name', 'LIKE', "%$search%");
                    })->orWhereHas('specificDealer', function ($query) use ($search) {
                        return $query->where('name', 'LIKE', "%$search%");
                    })
                        ->orWhere('make', 'LIKE', "%$search%")
                        ->orWhere('model', 'LIKE', "%$search%")
                        ->orWhere('start_at', 'LIKE', "%$search%")
                        ->orWhereHas('competitor', function ($query) use ($search) {
                            return $query->where('name', 'LIKE', "%$search%");
                        });
                }
            })
            ->when($this->isAccountManager(), function ($query) {
                return $query->where('internet_shops.dealer_id', auth()->user()->dealer_id);
            })
            ->when($this->isSpecificDealerManager(), function ($query) {
                return $query->where('internet_shops.dealer_id', auth()->user()->dealer_id)
                    ->where('internet_shops.specific_dealer_id', auth()->user()->specific_dealer_id);
            })
            ->when($this->isSalesperson(), function ($query) {
                if (auth()->user()->specific_dealer_id) {
                    return $query->where('internet_shops.dealer_id', auth()->user()->dealer_id)
                        ->where('internet_shops.specific_dealer_id', auth()->user()->specific_dealer_id);
                }

                return $query->where('internet_shops.dealer_id', auth()->user()->dealer_id);
            })
            ->when($request->sortBy, function ($query, $sortBy) use ($request) {
                $sortDesc = $request->sortDesc === 'true';

                if ($sortBy === 'specific_dealer') {
                    return $query->orderBySpecificDealerName($sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'competitor') {
                    return $query->orderByCompetitorName($sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'dealer') {
                    return $query->orderByDealerName($sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'make') {
                    return $sortDesc ? $query->orderByDesc('make') : $query->orderBy('make');
                }

                if ($sortBy === 'model') {
                    return $sortDesc ? $query->orderByDesc('model') : $query->orderBy('model');
                }

                if ($sortBy === 'shop_type') {
                    return $sortDesc ? $query->orderByDesc('is_shop_new') : $query->orderBy('is_shop_new');
                }

                if ($sortBy === 'type_of_dealer') {
                    return $sortDesc ? $query->orderByDesc('is_dealer') : $query->orderBy('is_dealer');
                }

                return $query->orderByDesc('start_at');
            })
            ->when(! isset($request->sortBy), function ($query) {
                return $query->orderByDesc('start_at');
            })
            ->paginate(10);

        return new InternetShopCollection($internetShops);
    }
}
