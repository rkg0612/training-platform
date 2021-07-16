<?php

namespace App\Services\InternetShop;

use App\Helpers\FilterRecords;
use App\Http\Resources\GroupShopCollection;
use App\Models\Group;
use App\Models\GroupShop;
use App\Models\GroupShopInternetShop;
use App\Models\Role;
use Illuminate\Support\Str;

class GroupShopService
{
    private $groupShop;

    private $user;

    public function __construct(GroupShop $groupShop)
    {
        $this->groupShop = $groupShop;
        $this->user = auth()->user();
    }

    public function index($fields)
    {
        $sortBy = $fields['sortBy'];
        $sortDesc = $fields['sortDesc'] ? 'desc' : 'asc';

        $groupShop = $this->setType($fields['types'], $fields['search']);

        if ($sortBy === 'undefined') {
            $groupShop = $groupShop->orderBy('created_at', $sortDesc);
        } elseif ($sortBy === 'dealer.name') {
            $groupShop = $groupShop->orderByDealerName($sortDesc);
        } elseif ($sortBy === 'name') {
            $groupShop = $groupShop->orderBy('name', $sortDesc);
        } else {
            $groupShop = $groupShop->orderBy('created_at', $sortDesc);
        }

        $groupShop->when($this->user, function ($query, $user) {
            if ($user->roles->contains('name', Role::ACCOUNT_MANAGER)) {
                $query = $query->where('dealer_id', $user->dealer_id);
            }

            if ($user->roles->contains('name', Role::SPECIFIC_DEALER_MANAGER)) {
                $query = $query->where('dealer_id', $user->dealer_id)
                    ->where('specific_dealer_id', $user->specific_dealer_id);
            }

            if ($user->roles->contains('name', Role::SALESPERSON)) {
                $query = $query->where('dealer_id', $user->dealer_id);

                if ($user->specific_dealer_id) {
                    $query = $query->where('specific_dealer_id', $user->specific_dealer_id);
                }
            }

            return $query;
        });

        return new GroupShopCollection($groupShop->paginate($fields['per_page'] ?? 10));
    }

    private function setType($type, $search = null)
    {
        $type = is_array($type) ? collect($type) : collect((explode(',', $type)));

        $relationships = ['internetShops', 'phoneShops', 'dealer', 'specificDealer', 'secretShopper'];

        $searchColumns = ['name', 'created_at', 'specificDealer.name', 'secretShopper.name'];

        return FilterRecords::make($this->groupShop, $type, $relationships, $searchColumns, $search)->get();
    }

    public function store($fields)
    {
        $groupShop = GroupShop::create([
            'name' => $fields['name'],
            'dealer_id' => $this->processDealerField($fields['dealer_id']),
            'specific_dealer_id' => $this->processSpecificDealer($fields['specific_dealer_id']),
            'user_id' => auth()->user()->id,
            'guest_view_id' => Str::uuid(),
            'hide_dealer_name' => $fields['hide_dealer_name'],
        ]);

        if (! empty($fields['internet_shops'])) {
            $groupShop->internetShops()->sync($fields['internet_shops']);
        }

        if (! empty($fields['phone_shops'])) {
            $groupShop->phoneShops()->sync($fields['phone_shops']);
        }

        return response()->json([
            'message' => 'Group shop created successfully!',
        ], 200);
    }

    public function update($fields, $id)
    {
        $groupShop = GroupShop::find($id);
        $groupShop->name = $fields['name'];
        $groupShop->dealer_id = $this->processDealerField($fields['dealer_id']);
        $groupShop->specific_dealer_id = $this->processSpecificDealer($fields['specific_dealer_id']);
        if (empty($groupShop->guest_view_id)) {
            $groupShop->guest_view_id = Str::uuid();
        }
        $groupShop->hide_dealer_name = $fields['hide_dealer_name'];
        $groupShop->save();

        if (! empty($fields['internet_shops'])) {
            $groupShop->internetShops()->sync($fields['internet_shops']);
        }

        if (! empty($fields['phone_shops'])) {
            $groupShop->phoneShops()->sync($fields['phone_shops']);
        }

        return response()->json([
            'message' => 'Group shop created successfully!',
        ], 200);
    }

    public function show($id)
    {
        $groupShop = GroupShop::query()
            ->where('id', $id)
            ->orWhere('guest_view_id', $id)
            ->with([
                'dealer', 'specificDealer',
                'internetShops.competitor',
                'internetShops.specificDealer', 'internetShops.source', 'internetShops.chatScreenshots' => function ($screenshot) {
                    return $screenshot->orderBy('order_by', 'desc');
                }, 'internetShops.emailScreenshots' => function ($screenshot) {
                    return $screenshot->orderBy('order_by', 'desc');
                }, 'internetShops.specificDealer', 'internetShops.phoneNumber.sms', 'internetShops.phoneNumber.calls' => function ($call) {
                    return $call->orderBy('created_at', 'desc');
                },
                'phoneShops.specificDealer', 'phoneShops.dealerRecordings', 'phoneShops.competitorRecordings',
            ])
            ->firstOrFail();

        return $groupShop;
    }

    public function destroy($id)
    {
        GroupShop::destroy($id);

        return response()->json([
            'message' => 'Group shop successfully archived',
        ], 200);
    }

    public function restore($id)
    {
        GroupShop::withTrashed()->find($id)->restore();

        return response()->json([
            'message' => 'Group shop successfully archived',
        ], 200);
    }

    private function processDealerField($dealer): int
    {
        if ($this->user->roles->contains('name', Role::ACCOUNT_MANAGER) ||
            $this->user->roles->contains('name', Role::SPECIFIC_DEALER_MANAGER)) {
            return $this->user->dealer_id;
        }

        return $dealer;
    }

    private function processSpecificDealer($specificDealer): int
    {
        if (empty($specificDealer)) {
            return $this->user->specific_dealer_id;
        }

        if ($this->user->roles->contains('name', Role::SPECIFIC_DEALER_MANAGER)) {
            return $this->user->specific_dealer_id;
        }

        return $specificDealer;
    }
}
