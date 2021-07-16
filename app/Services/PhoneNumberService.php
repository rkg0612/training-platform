<?php

namespace App\Services;

use App\Models\PhoneNumber;
use App\Services\Twilio\BuyNumberService;
use App\Services\Twilio\ReleaseNumberService;
use App\Services\Twilio\SetWebHookEndpoint;

class PhoneNumberService
{
    private $phoneNumber;
    private $buyNumberService;
    private $releaseNumberService;
    private $setWebHookEndpoint;

    public function __construct(Phonenumber $phoneNumber, BuyNumberService $buyNumberService, ReleaseNumberService $releaseNumberService, SetWebHookEndpoint $setWebHookEndpoint)
    {
        $this->phoneNumber = $phoneNumber;
        $this->buyNumberService = $buyNumberService;
        $this->releaseNumberService = $releaseNumberService;
        $this->setWebHookEndpoint = $setWebHookEndpoint;
    }

    public function index($request)
    {
    }

    public function fetchNumbersToManage($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $page = isset($request->current_page) ? $request->current_page : 1;
        $sortBy = isset($request->sortBy) ? $request->sortBy : '';
        $sortDesc = $request->has('sortDesc') && $request->sortDesc == 'true' ? 'DESC' : 'ASC';
        $search = $request->search;

        $filtersList = ['inactive', 'active', 'active,inactive'];
        if (! in_array(strtolower($request->filters), $filtersList)) {
            abort(404, 'No Filter selected!');
        }
//        switch ($request->filters) {
//            case 'Inactive':
//                $model = $this->phoneNumber->onlyTrashed()
//                    ->with(['dealer', 'user', 'state', 'voiceMail']);
//        break;
//            case 'Active':
//                    $model = $this->phoneNumber->with(['dealer', 'user', 'state', 'voiceMail']);
//        break;
//            case 'Active,Inactive':
//            case 'Inactive,Active':
//                $model = $this->phoneNumber->withTrashed()
//                    ->with(['dealer', 'user', 'state', 'voiceMail']);
//        break;
//        }

        if ('user.name' == $sortBy) {
            if (strtolower($request->filters) == 'inactive') {
                $numbers = $this->phoneNumber->onlyTrashed()->join('users', 'users.id', '=', 'phone_numbers.user_id')->with(['dealer', 'state', 'voiceMail', 'user'])->orderBy('users.name', $sortDesc);
            } elseif (strtolower($request->filters) == 'active') {
                $numbers = $this->phoneNumber->join('users', 'users.id', '=', 'phone_numbers.user_id')->with(['dealer', 'state', 'voiceMail', 'user'])->orderBy('users.name', $sortDesc);
            } elseif (strtolower($request->filters) == 'active,inactive' || strtolower($request->filters) == 'inactive,active') {
                $numbers = $this->phoneNumber->withTrashed()->join('users', 'users.id', '=', 'phone_numbers.user_id')->with(['dealer', 'state', 'voiceMail', 'user'])->orderBy('users.name', $sortDesc);
            }
        } elseif ('dealer.name' == $sortBy) {
            if (strtolower($request->filters) == 'inactive') {
                $numbers = $this->phoneNumber->onlyTrashed()->join('dealers', 'dealers.id', '=', 'phone_numbers.dealer_id')->with(['dealer', 'state', 'voiceMail', 'user'])->orderBy('dealers.name', $sortDesc);
            } elseif (strtolower($request->filters) == 'active') {
                $numbers = $this->phoneNumber->join('dealers', 'dealers.id', '=', 'phone_numbers.dealer_id')->with(['dealer', 'state', 'voiceMail', 'user'])->orderBy('dealers.name', $sortDesc);
            } elseif (strtolower($request->filters) == 'active,inactive' || strtolower($request->filters) == 'inactive,active') {
                $numbers = $this->phoneNumber->withTrashed()->join('dealers', 'dealers.id', '=', 'phone_numbers.dealer_id')->with(['dealer', 'state', 'voiceMail', 'user'])->orderBy('dealers.name', $sortDesc);
            }
        } else {
            if (strtolower($request->filters) == 'inactive') {
                $numbers = $this->phoneNumber->onlyTrashed()->with(['dealer', 'user', 'state', 'voiceMail']);
            } elseif (strtolower($request->filters) == 'active') {
                $numbers = $this->phoneNumber->with(['dealer', 'user', 'state', 'voiceMail']);
            } elseif (strtolower($request->filters) == 'active,inactive' || strtolower($request->filters) == 'inactive,active') {
                $numbers = $this->phoneNumber->withTrashed()->with(['dealer', 'user', 'state', 'voiceMail']);
            }
        }

        if (! empty($sortBy) && 'user.name' != $sortBy && 'dealer.name' != $sortBy) {
            $numbers = $numbers->orderBy($sortBy, $sortDesc);
        }

        if (! empty($search)) {
            $perPage = 5;
            $page = 1;
            $numbers->where('value', 'like', '%'.$search.'%');
        }

        return response([
            'total' => $numbers->count(),
            'data' => $numbers->forPage($page, $perPage)->get(),
            'current_page' => $page,
            'per_page' => $perPage,
        ]);
    }

    public function searchNumbers($request)
    {
        $search = $request->search;

        $numbers = $this->phoneNumber->withTrashed()->where('value', 'like', '%'.$search.'%')->get();

        return response([
            'numbers' => $numbers,
        ]);
    }

    public function store($request)
    {
        $responseFromTwilio = $this->buyNumberService->buy($request->number);

        if ($responseFromTwilio) {
            $phoneNumber = $this->createRecord($request, $responseFromTwilio);

            $this->setWebHookEndpoint->set($request['number']);

            $phoneNumber = PhoneNumber::where('id', $phoneNumber->id)->with(['dealer', 'user', 'state', 'voiceMail'])->get();

            return response()->json($phoneNumber);
        }

        abort(500);
    }

    public function createRecord($request, $phoneNumber)
    {
        return $this->phoneNumber->create([
            'state_id'      => $request->state,
            'area_codes'    => $request->area_code,
            'value'         => $phoneNumber->phoneNumber,
            'dealer_id'     => $request->dealer,
            'voice_mail_id' => $request->audio,
            'is_dealer'     => $request->category,
            'user_id'       => $request->secret_shopper,
            'sid' => $phoneNumber->sid,
            'formatted_value' => $request->number,
        ]);
    }

    public function update($request)
    {
        $phoneNumber = $this->phoneNumber->find($request->id);
        $phoneNumber->state_id = $request->state;
        $phoneNumber->area_codes = $request->area_code;
        $phoneNumber->value = $request->number;
        $phoneNumber->user_id = $request->secret_shopper;
        $phoneNumber->dealer_id = $request->dealer;
        $phoneNumber->voice_mail_id = $request->audio;
        $phoneNumber->is_dealer = $request->category;
        $phoneNumber->save();
        $phoneNumber = $this->phoneNumber->with(['dealer', 'user', 'state', 'voiceMail'])->find($request->id);

        return response()->json($phoneNumber);
    }

    public function destroy($ids)
    {
        $phoneNumbers = $this->phoneNumber->whereIn('id', $ids->all())->get();
        $phoneNumbers->each(function ($phoneNumber) {
            if ($this->releaseNumberService->release($phoneNumber->value)) {
                $phoneNumber->delete();
            }
        });

        return [
            'success' => true,
        ];
    }
}
