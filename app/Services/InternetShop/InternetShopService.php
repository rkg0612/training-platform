<?php

namespace App\Services\InternetShop;

use App\Helpers\WithFileUpload;
use App\Http\Resources\InternetShopCollection;
use App\Models\InternetShop;
use App\Models\LeadSource;
use App\Models\PhoneNumberSMSLog;
use App\Models\Role;
use App\Models\SpecificDealer;
use App\Services\Twilio\TwilioClient;
use App\Services\Twilio\TwilioHelper;
use App\SpecificDealerCompetitor;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class InternetShopService
{
    use WithFileUpload, TwilioHelper;

    private $chatScreenshotService;
    private $emailScreenshotService;

    public function __construct(ChatScreenshotService $chatScreenshotService, EmailScreenshotService $emailScreenshotService)
    {
        $this->chatScreenshotService = $chatScreenshotService;
        $this->emailScreenshotService = $emailScreenshotService;
    }

    public function groupShopRequest($request)
    {
        $user = auth()->user();
        $internetShop = InternetShop::with(['dealer', 'specificDealer', 'competitor'])
            ->where(function ($query) use ($user) {
                if ($user->roles->containsStrict('name', 'account-manager')) {
                    $query->where('dealer_id', $user->dealer_id);

                    return;
                }

                if ($user->roles->containsStrict('name', 'specific-dealer-manager')) {
                    $query->where('specific_dealer_id', $user->specific_dealer_id);

                    return;
                }
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return new InternetShopCollection($internetShop);
    }

    public function index($request)
    {
        if (isset($request['isGroupShop'])) {
            return $this->groupShopRequest($request);
        }

        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $page = isset($request->current_page) ? $request->current_page : 1;
        $sortBy = isset($request->sortBy) ? $request->sortBy : '';
        $sortDesc = $request->has('sortDesc') && $request->sortDesc == 'true' ? 'DESC' : 'ASC';
        $paginate = $request->perPage ? $request->perPage : 5;
        $search = $request->search;
        $status = $request->status;

        // dealer.name posted_by.name specific_dealer.name, category
        $currentUser = auth()->user();
        $records = InternetShop::where(function ($query) use ($currentUser) {
            if ($currentUser->roles->pluck('name')->contains(Role::SPECIFIC_DEALER_MANAGER)) {
                $query->whereNotNull('specific_dealer_id')->where('specific_dealer_id', $currentUser->specific_dealer_id);
            }

            if ($currentUser->roles->pluck('name')->contains(Role::ACCOUNT_MANAGER)) {
                $query->whereNotNull('dealer_id')->where('dealer_id', $currentUser->dealer_id);
            }
        })->with(['source', 'state', 'city', 'chatScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'desc');
        }, 'emailScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'postedBy', 'specificDealer', 'specificDealer.competitors', 'dealer', 'competitor']);

        // User::with(['blitzNumbers' => function ($q) {$q->orderBy('phone_number');}])->get();
        if (! empty($search)) {
            $records->where('lead_phone_number', 'like', '%'.$search.'%');
            $records->orWhereHas('dealer', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })->orWhereHas('specificDealer', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })->orWhereHas('postedBy', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        if ($status == 'Inactive') {
            $records->onlyTrashed();
        } elseif ($status == 'Inactive,Active') {
            $records->withTrashed();
        }

        if ($sortBy == 'posted_by.name') {
            if ($sortDesc == 'DESC') {
                $records = $records->get()->sortByDesc('postedBy.name');
            } else {
                $records = $records->get()->sortBy('postedBy.name');
            }
        }

        if ($sortBy == 'dealer.name') {
            if ($sortDesc == 'DESC') {
                $records = $records->get()->sortByDesc('dealer.name');
            } else {
                $records = $records->get()->sortBy('dealer.name');
            }
        }

        if ($sortBy == 'specific_dealer.name') {
            if ($sortDesc == 'DESC') {
                $records = $records->get()->sortByDesc('specificDealer.name');
            } else {
                $records = $records->get()->sortBy('specificDealer.name');
            }
        }

        if ($sortBy == 'created_at') {
            if ($sortDesc == 'DESC') {
                $records = $records->get()->sortByDesc('created_at');
            } else {
                $records = $records->get()->sortBy('created_at');
            }
        }

        if ($sortBy != 'posted_by.name' && $sortBy != 'dealer.name' && $sortBy != 'specific_dealer.name' && $sortBy != 'created_at') {
            $records = $records->latest()->get();
        }

        //		$records = $records->values();

        //		return $records->paginate($paginate)->appends(['category']);
        return response([
            'total' => $records->count(),
            'data' => $records->forPage($page, $perPage)->values(),
            'per_page' => $perPage,
        ]);
    }

    //    Unused, function moved to InternetPostController
    public function store($fields, $files)
    {
        $lead_value = $fields['lead']['source'];
        if (is_int($lead_value)) {
            $lead_id = LeadSource::findOrFail($lead_value);
            $lead_id = $lead_id->id;
        } else {
            LeadSource::insertOrIgnore(['value' => $lead_value]);
            $lead_id = LeadSource::where('value', $lead_value)->first()->id;
        }

        //Check if this is new lead source
        $table = new InternetShop;
        $table->user_id = $fields['shop']['secretShopperId'];

        // Dealer
        $table->is_dealer = $fields['dealer']['is_dealer'] ? 1 : 0;
        $table->dealer_id = $fields['dealer']['id'];
        $table->zipcode = $fields['dealer']['zipcode'];
        $table->timezone = $fields['dealer']['timezone'];
        // Specifid Dealer name
        $specificDealerId = $this->findOrCreateRecordSpecificDealer($fields['dealer']['id'], $fields['dealer']['name']);
        $table->specific_dealer_id = $specificDealerId;
        // Competitor
        $competitorId = $this->findOrCreateRecordCompetitor($specificDealerId, $fields);
        $table->competitor_id = $competitorId;
        // add column for competitor in internet shops

        // Location
        $table->state_id = $fields['location']['state'];
        $table->city_id = $fields['location']['city'];
        // Shop
        $table->salesperson_name = $fields['shop']['salesPersonName'];
        $table->is_shop_new = $fields['shop']['is_new'];
        $table->source_link = $fields['shop']['sourceLink'];
        $table->csm_name = $fields['shop']['csmName'];
        $table->call_guide_link = $fields['shop']['callGuideLink'];
        $table->start_at = Carbon::parse($fields['shop']['startAt']);
        $table->shop_duration = $fields['shop']['duration'];
        // Lead
        $table->lead_source = $lead_id;
        $table->lead_name = $fields['lead']['name'];
        $table->lead_email = $fields['lead']['email'];
        $table->lead_phone_number = $fields['lead']['phoneNumber'];
        // Car
        $table->vehicle_identification_number = $fields['car']['vin'];
        $table->make = $fields['car']['make'];
        $table->model = $fields['car']['model'];

        // Call
        $table->call_first_received = ($fields['call']['first']['received']) ? Carbon::parse($fields['call']['first']['received']) : null;
        $table->call_response_time = $fields['call']['first']['response'];
        $table->call_attempts = (! empty($fields['call']['attempts']) && is_int($fields['call']['attempts'])) ? $fields['call']['attempts'] : 0;
        // Sms
        $table->sms_first_received = ($fields['sms']['first']['received']) ? Carbon::parse($fields['sms']['first']['received']) : null;
        $table->sms_response_time = $fields['sms']['first']['response'];
        $table->sms_attempts = (! empty($fields['sms']['attempts']) && is_int($fields['sms']['attempts'])) ? $fields['sms']['attempts'] : 0;
        // Email
        $table->email_first_received = ($fields['email']['first']['received']) ? Carbon::parse($fields['email']['first']['received']) : null;
        $table->email_response_time = $fields['email']['first']['response'];
        $table->email_attempts = (! empty($fields['email']['attempts']) && is_int($fields['email']['attempts'])) ? $fields['email']['attempts'] : 0;
        $table->email_second_received = ($fields['email']['second']['received']) ? Carbon::parse($fields['email']['second']['received']) : null;
        $table->email_second_response_time = $fields['email']['second']['response'];
        // Chat
        $table->chat_first_received = ($fields['chat']['first']['received']) ? Carbon::parse($fields['chat']['first']['received']) : null;
        $table->chat_response_time = $fields['chat']['first']['response'];
        $table->chat_attempts = (! empty($fields['chat']['attempts']) && is_int($fields['chat']['attempts'])) ? $fields['chat']['attempts'] : 0;
        // Verification Screenshots
        $table->verification_screenshot = $this->saveImageAs($files['verificationScreenshot'], 'verification-screenshots/', 'webp', 's3');
        $table->verification_screenshot_fallback = $this->saveImageAs($files['verificationScreenshot'], 'verification-screenshots/', 'jpg', 's3');

        $table->report_content = isset($fields['report_content']) ? $fields['report_content'] : '';
        $table->save();

        // Additional Screenshots
        if (isset($fields['emailScreenshots']) && ! empty($fields['emailScreenshots'])) {
            $this->emailScreenshotService->store($table->id, collect($fields['emailScreenshots']));
        }

        return InternetShop::with(['source', 'competitor', 'dealer', 'chatScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'emailScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'postedBy', 'specificDealer', 'state', 'city'])->findOrFail($table->id);
    }

    public function destroy($id)
    {
        if (InternetShop::find($id)->delete()) {
            return response('success');
        }
    }

    //    Unused, function moved to InternetPostController
    public function update($fields, $files, $id)
    {
        $lead_value = $fields['lead']['source'];
        if (is_int($lead_value)) {
            $lead_id = LeadSource::findOrFail($lead_value);
            $lead_id = $lead_id->id;
        } else {
            LeadSource::insertOrIgnore(['value' => $lead_value]);
            $lead_id = LeadSource::where('value', $lead_value)->first()->id;
        }

        $table = InternetShop::find($id);
        $table->user_id = $fields['shop']['secretShopperId'];
        // Dealer
        $table->is_dealer = $fields['dealer']['is_dealer'] ? 1 : 0;
        $table->dealer_id = $fields['dealer']['id'];
        $table->timezone = $fields['dealer']['timezone'];
        // Specifid Dealer name
        $specificDealerId = $this->findOrCreateRecordSpecificDealer($fields['dealer']['id'], $fields['dealer']['name']);
        $table->specific_dealer_id = $specificDealerId;

        $competitorId = $this->findOrCreateRecordCompetitor($specificDealerId, $fields);
        $table->competitor_id = $competitorId;

        // Location
        $table->state_id = $fields['location']['state'];
        $table->city_id = $fields['location']['city'];
        // Shop
        $table->salesperson_name = $fields['shop']['salesPersonName'];
        $table->is_shop_new = $fields['shop']['is_new'];
        $table->source_link = $fields['shop']['sourceLink'];
        $table->csm_name = $fields['shop']['csmName'];
        $table->call_guide_link = $fields['shop']['callGuideLink'];
        $table->start_at = Carbon::parse($fields['shop']['startAt']);
        $table->shop_duration = $fields['shop']['duration'];
        // Lead
        $table->lead_source = $lead_id;
        $table->lead_name = $fields['lead']['name'];
        $table->lead_email = $fields['lead']['email'];
        $table->lead_phone_number = $fields['lead']['phoneNumber'];
        // Car
        $table->vehicle_identification_number = $fields['car']['vin'];

        // Call
        $table->call_first_received = ($fields['call']['first']['received']) ? Carbon::parse($fields['call']['first']['received']) : null;
        $table->call_response_time = $fields['call']['first']['response'];
        $table->call_attempts = $fields['call']['attempts'] ?? 0;
        // Sms
        $table->sms_first_received = ($fields['sms']['first']['received']) ? Carbon::parse($fields['sms']['first']['received']) : null;
        $table->sms_response_time = $fields['sms']['first']['response'];
        $table->sms_attempts = $fields['sms']['attempts'] ?? 0;
        // Email
        $table->email_first_received = ($fields['email']['first']['received']) ? Carbon::parse($fields['email']['first']['received']) : null;
        $table->email_response_time = $fields['email']['first']['response'];
        $table->email_attempts = $fields['email']['attempts'] ?? 0;
        $table->email_second_received = ($fields['email']['second']['received']) ? Carbon::parse($fields['email']['second']['received']) : null;
        $table->email_second_response_time = $fields['email']['second']['response'];
        // Chat
        $table->chat_first_received = ($fields['chat']['first']['received']) ? Carbon::parse($fields['chat']['first']['received']) : null;
        $table->chat_response_time = $fields['chat']['first']['response'];
        $table->chat_attempts = $fields['chat']['attempts'] ?? 0;
        // Screenshots
        if (isset($files['verificationScreenshot'])) {
            $table->verification_screenshot = $this->saveImageAs($files['verificationScreenshot'], 'verification-screenshots/', 'webp', 's3');
            $table->verification_screenshot_fallback = $this->saveImageAs($files['verificationScreenshot'], 'verification-screenshots/', 'jpg', 's3');
        }

        //        $table->chatScreenshots()->delete();
//        $table->emailScreenshots()->delete();
        if (! empty($fields['existingChatScreenshots'])) {
            $existingChatScreenshots = $fields['existingChatScreenshots'];
            $table->chatScreenshots()->whereNotIn('id', $existingChatScreenshots)->delete();
//        	foreach ($existingChatScreenshots as $screenshot) {
//	        }
        }

        if (! empty($fields['existingEmailScreenshots'])) {
            $existingEmailScreenshots = $fields['existingEmailScreenshots'];
            $table->emailScreenshots()->whereNotIn('id', $existingEmailScreenshots)->delete();
//        	foreach ($existingEmailScreenshots as $screenshot) {
//        	    $table->emailScreenshots()->whereNotIn('id', $existingEmailScreenshots)->delete();
//	        }
        }

        $table->emailScreenshots()->update(['internet_shop_id' => null]);
        if (isset($fields['emailScreenshots']) && ! empty($fields['emailScreenshots'])) {
            $this->emailScreenshotService->update($table->id, collect($fields['emailScreenshots']));
        }

        if (isset($files['chatScreenshots']) && ! empty($files['chatScreenshots'])) {
            $chatScreenshots = isset($files['chatScreenshots']) ? collect($files['chatScreenshots']) : collect([]);
//            $this->chatScreenshotService->update($table->id, $chatScreenshots, $fields['chatScreenshotOrder']);
        }

        // report content
        $table->report_content = isset($fields['report_content']) ? $fields['report_content'] : '';

        $table->save();

        return InternetShop::with(['source', 'competitor', 'dealer', 'chatScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'emailScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'postedBy', 'specificDealer', 'state', 'city'])->findOrFail($id);
    }

    public function findOrCreateRecordSpecificDealer($id, $name)
    {
        $dealer = SpecificDealer::findPerson($id, $name)->first();
        if (! $dealer) {
            $dealer = new SpecificDealer;
            $dealer->dealer_id = $id;
            $dealer->name = $name;
            $dealer->save();

            return $dealer->id;
        }

        return $dealer->id;
    }

    public function findOrCreateRecordCompetitor($specificDealerId, $fields)
    {
        if ($fields['dealer']['is_dealer']) {
            return;
        }

        $field = $fields['dealer']['competitor'];
        $competitor = SpecificDealerCompetitor::findRecord($specificDealerId, $field)->first();

        if (empty($competitor)) {
            $competitor = new SpecificDealerCompetitor;
            $competitor->specific_dealer_id = $specificDealerId;
            $competitor->name = $field;
            $competitor->save();

            return $competitor->id;
        }

        return $competitor->id;
    }

    public function show($id)
    {
        return InternetShop::with(['source', 'competitor', 'dealer', 'dealer.options', 'truecar_fields', 'chatScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'desc');
        }, 'emailScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'postedBy', 'specificDealer', 'state', 'city', 'phoneNumber.sms' => function ($message) {
            return $message->orderBy('start_at', 'asc');
        }, 'phoneNumber.sms.medias', 'phoneNumber.calls'])->findOrFail($id);
    }

    public function restore($id)
    {
        if (InternetShop::onlyTrashed()->find($id)->restore()) {
            return [
                'success' => true,
            ];
        }
    }

    public function fetchSms($id)
    {
        try {
            $client = new Client(
                config('twilio.sid'),
                config('twilio.token')
            );
        } catch (ConfigurationException $e) {
            Log::critical('Twilio Credentials is not valid'.$e->getMessage());
        }

        $shop = InternetShop::findOrFail($id);
        $fetchedMessages = $client->messages->read([
            'to' => $shop->phoneNumber->value,
        ]);

        foreach (array_reverse($fetchedMessages) as $record) {
            if (is_null(PhoneNumberSMSLog::where('sms_message_sid', $record->sid)->first())) {
                PhoneNumberSMSLog::create([
                    'phone_number_id' => $shop->phoneNumber->id,
                    'value' => $record->body,
                    'start_at' => $record->dateSent->setTimezone(new \DateTimeZone('America/New_York')),
                    'from' => $this->getFormattedNumber($record->from),
                    'to' => $this->getFormattedNumber($record->to),
                    'sms_message_sid' => $record->sid,
                ]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
