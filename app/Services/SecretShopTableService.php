<?php

namespace App\Services;

use App\Models\Dealer;
use App\Models\InternetShop;
use App\Models\PhoneShop;
use App\Models\Role;
use App\Models\SpecificDealer;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SecretShopTableService
{
    private $searchText = '';
    private $dateFormat = 'm-d-Y h:i:s A';
    private $isSpecificDealerManager;
    private $isSalesperson;
    private $isAccountManager;
    private $isDealerTrueCar;
    private $sortBy;
    private $sortDesc;
    private $page;
    private $phoneShopCount;

    public function __construct()
    {
        $this->isSpecificDealerManager = auth()->user()->roles->contains('name', Role::SPECIFIC_DEALER_MANAGER);
        $this->isSalesperson = auth()->user()->roles->contains('name', Role::SALESPERSON);
        $this->isAccountManager = auth()->user()->roles->contains('name', Role::ACCOUNT_MANAGER);
        $this->isDealerTrueCar = Dealer::find(auth()->user()->dealer_id)->name === 'TrueCar';
    }

    public function index()
    {
        $this->sortBy = request()->get('sortBy');
        $this->sortDesc = request()->get('sortDesc');
        $this->searchText = request()->get('search');
        $this->page = request()->get('page') * 10;

        return $this->internetShopResult(request()->get('page'));
    }

    public function internetShopResult($page)
    {
        $pagination = InternetShop::query()
            ->with(['specificDealer', 'dealer', 'competitor'])
            ->when($this->sortBy, function ($query, $sortBy) {
                if ($sortBy === 'dealerName') {
                    return $query->orderByDealerName($this->sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'specificDealerName') {
                    return $query->orderBySpecificDealerName($this->sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'dateShopped') {
                    return $this->sortDesc ? $query->orderByDesc('start_at') : $query->orderBy('created_at');
                }

                if ($sortBy === 'make') {
                    return $this->sortDesc ? $query->orderByDesc('make') : $query->orderBy('make');
                }

                if ($sortBy === 'model') {
                    return $this->sortDesc ? $query->orderByDesc('model') : $query->orderBy('model');
                }

                if ($sortBy === 'csmName') {
                    return $this->sortDesc ? $query->orderByDesc('csm_name') : $query->orderBy('csm_name');
                }

                if ($sortBy === 'salespersonsName') {
                    return $this->sortDesc ? $query->orderByDesc('salesperson_name') : $query->orderBy('salesperson_name');
                }

                return $query->orderByDesc('start_at');
            })
            ->when($this->isAccountManager, function ($query) {
                return $query
                    ->where('internet_shops.dealer_id', auth()->user()->dealer_id);
            })
            ->when($this->isSpecificDealerManager, function ($query) {
                return $query
                    ->where('internet_shops.dealer_id', auth()->user()->dealer_id)
                    ->where('internet_shops.specific_dealer_id', auth()->user()->specific_dealer_id);
            })
            ->when($this->isSalesperson, function ($query) {
                $query = $query->where('internet_shops.dealer_id', auth()->user()->dealer_id);

                if (auth()->user()->specific_dealer_id) {
                    $query = $query->where('internet_shops.specific_dealer_id', auth()->user()->specific_dealer_id);
                }

                return $query;
            })
            ->when(! empty($this->searchText), function ($query) {
                $specificDealers = SpecificDealer::query()
                    ->where('dealer_id', auth()->user()->dealer_id)
                    ->pluck('id')
                    ->toArray();

                if ($this->isAccountManager) {
                    $query = $query
                            ->whereHas('dealer', function ($query) {
                                return $query
                                    ->where('name', 'LIKE', "%$this->searchText%")
                                    ->where('id', auth()->user()->dealer_id);
                            })
                            ->whereHas('specificDealer', function ($query) {
                                return $query
                                    ->where('name', 'LIKE', "%$this->searchText%")
                                    ->where('dealer_id', auth()->user()->dealer_id);
                            })
                            ->whereHas('competitor', function ($query) use ($specificDealers) {
                                return $query
                                    ->where('name', 'LIKE', "%$this->searchText%")
                                    ->whereIn('specific_dealer_id', $specificDealers);
                            });
                }

                if ($this->isSpecificDealerManager) {
                    $query = $query
                        ->whereHas('dealer', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('id', auth()->user()->dealer_id);
                        })
                        ->whereHas('specificDealer', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('id', auth()->user()->specific_dealer_id);
                        })
                        ->whereHas('competitor', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('specific_dealer_id', auth()->user()->specific_dealer_id);
                        });
                }

                if ($this->isSalesperson) {
                    $query = $query
                        ->whereHas('dealer', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('id', auth()->user()->dealer_id);
                        })
                        ->whereHas('specificDealer', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('id', auth()->user()->specific_dealer_id);
                        })
                        ->whereHas('competitor', function ($query) use ($specificDealers) {
                            if (! empty(auth()->user()->specific_dealer_id)) {
                                return $query
                                    ->where('name', 'LIKE', "%$this->searchText%")
                                    ->where('specific_dealer_id', auth()->user()->specific_dealer_id);
                            }

                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('specific_dealer_id', $specificDealers);
                        });
                }

                return $query;
            })
            ->get();

        $totalCount = $pagination->count();

        $items = $pagination
            ->skip($this->page - 10)
            ->take(10)
            ->map(function ($internetShop) {
                return [
                    'id' => $internetShop->id,
                    'dealerName' =>  $internetShop->dealer->name,
                    'specificDealerName' => $this->getSpecificName($internetShop),
                    'dateShopped' => Carbon::parse($internetShop->start_at)->format($this->dateFormat),
                    'dealerType' => $internetShop->is_dealer ? 'Dealer' : 'Competitor',
                    'salespersonsName' => $internetShop->salesperson_name,
                    'csmName' => $internetShop->dealer->name === 'TrueCar' ? $internetShop->csmName : null,
                    'shopType' => 'Internet Shop',
                    'make' => $internetShop->make,
                    'model' => $internetShop->model,
                ];
            });

        $phoneShops = $this->phoneShopResult();

        if ($phoneShops->isNotEmpty()) {
            $items = $this->sortCollections($items->merge($phoneShops->toArray()));
            $totalCount = $this->phoneShopCount + $totalCount;
        } else {
            $items = $this->sortCollections($items);
        }

        return new LengthAwarePaginator(
            $items,
            $totalCount,
            count($items),
            $page,
            [
                'path' => \Request::url(),
                'query' => [
                    'page' => $page,
                ],
            ]
        );
    }

    private function sortCollections(Collection $shops)
    {
        if (! empty($this->sortBy)) {
            $shops = $this->sortDesc ? $shops->sortByDesc($this->sortBy) : $shops->sortBy($this->sortBy);
        } else {
            $shops = $shops->sortByDesc('dateShopped');
        }

        return $shops->values();
    }

    public function phoneShopResult()
    {
        $pagination = PhoneShop::with(['specificDealer', 'dealer'])
            ->with(['specificDealer', 'dealer'])
            ->when($this->sortBy, function ($query, $sortBy) {
                if ($sortBy === 'dealerName') {
                    return $query->orderByDealerName($this->sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'dateShopped') {
                    return $this->sortDesc ? $query->orderByDesc('created_at') : $query->orderBy('created_at');
                }

                if ($sortBy === 'specificDealerName') {
                    return $query->orderBySpecificDealerName($this->sortDesc ? 'desc' : 'asc');
                }

                if ($sortBy === 'dateShopped') {
                    return $this->sortDesc ? $query->orderByDesc('start_date') : $query->orderBy('created_at');
                }

                if ($sortBy === 'salespersonsName') {
                    return $this->sortDesc ? $query->orderByDesc('sales_person_name') : $query->orderBy('sales_person_name');
                }

                return $query->orderByDesc('start_date');
            })
            ->when($this->isSalesperson, function ($query) {
                $query = $query->where('phone_shops.dealer_id', auth()->user()->dealer_id);

                if (auth()->user()->specific_dealer_id) {
                    $query = $query->where('phone_shops.specific_dealer_id', auth()->user()->specific_dealer_id);
                }

                return $query;
            })
            ->when($this->isAccountManager, function ($query) {
                return $query
                    ->where('phone_shops.dealer_id', auth()->user()->dealer_id);
            })
            ->when($this->isSpecificDealerManager, function ($query) {
                return $query
                    ->where('phone_shops.dealer_id', auth()->user()->dealer_id)
                    ->where('phone_shops.specific_dealer_id', auth()->user()->specific_dealer_id);
            })
            ->when(! empty($this->searchText), function ($query) {
//                $query = $query
//                    ->where('sales_person_name', 'LIKE', "%$this->searchText%")
//                    ->orWhere('start_date', 'LIKE', "%$this->searchText%");

                if ($this->isAccountManager) {
                    $query = $query
                        ->whereHas('dealer', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('id', auth()->user()->dealer_id);
                        })
                        ->whereHas('specificDealer', function ($query) {
                            return $query
                                ->where('name', 'LIKE', "%$this->searchText%")
                                ->where('dealer_id', auth()->user()->dealer_id);
                        });
                }

                return $query;
            })
            ->get();

        $this->phoneShopCount = $pagination->count();

        return $pagination
            ->skip($this->page - 10)
            ->take(10)
            ->map(function ($phoneShop) {
                return [
                    'id' => $phoneShop->id,
                    'dealerName' =>  $phoneShop->dealer()->first()->name,
                    'specificDealerName' => $phoneShop->specificDealer()->first()->name,
                    'dateShopped' => Carbon::parse($phoneShop->start_date)->format($this->dateFormat),
                    'dealerType' => 'Dealer',
                    'salespersonsName' => $phoneShop->sales_person_name,
                    'csmName' => null,
                    'shopType' => 'Phone Shop',
                    'make' => null,
                    'model' => null,
                ];
            });
    }

    private function getSpecificName($internetShop)
    {
        if ($internetShop->specificDealer) {
            return $internetShop->specificDealer->name;
        }

        if ($internetShop->competitor) {
            return $internetShop->competitor->name;
        }

        return null;
    }
}
