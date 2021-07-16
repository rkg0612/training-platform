<?php

namespace App\Services\InternetShop;

use App\Helpers\WithFileUpload;
use App\Http\Resources\InternetShopCollection;
use App\Models\InternetShop;
use App\Models\LeadSource;
use App\Models\PhoneNumberSMSLog;
use App\Models\Role;
use App\Models\SpecificDealer;
use App\Models\TrueCarField;
use App\Services\Twilio\TwilioClient;
use App\Services\Twilio\TwilioHelper;
use App\SpecificDealerCompetitor;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class InternetPostService
{
    use WithFileUpload, TwilioHelper;

    private $chatScreenshotService;
    private $emailScreenshotService;

    public function __construct(ChatScreenshotService $chatScreenshotService, EmailScreenshotService $emailScreenshotService)
    {
        $this->chatScreenshotService = $chatScreenshotService;
        $this->emailScreenshotService = $emailScreenshotService;
    }

    public function store($request)
    {
        $internetshop = new InternetShop;

        $internetshop->fill($request->all());
        $internetshop->is_dealer = $request->is_dealer == 'true' ? 1 : 0;

        if ($lead = LeadSource::find($request->lead_source)) {
            $lead_id = $lead->id;
        } else {
            $lead_id = LeadSource::insertOrIgnore(['value' => $request->lead_source])->id;
        }

        $internetshop->lead_source = $lead_id;

        if ($specific_dealer = SpecificDealer::find($request->specific_dealer_id)) {
            $specificDealerId = $specific_dealer->id;
        } else {
            $specificDealerId = SpecificDealer::firstOrCreate(['name' => $request->specific_dealer_id, 'dealer_id' => $request->dealer_id])->id;
        }
        $internetshop->specific_dealer_id = $specificDealerId;

        $competitorId = $request->competitor_id;

        if (isset($competitorId) && $competitorId !== 'null' && $competitorId !== null) {
            if ($competitor = SpecificDealerCompetitor::find($request->competitor_id)) {
                $competitorId = $competitor->id;
            } else {
                $competitorId = SpecificDealerCompetitor::firstOrCreate(['name' => $request->competitor_id, 'specific_dealer_id' => $specificDealerId])->id;
            }
        } else {
            $competitorId = null;
        }

        $internetshop->competitor_id = $competitorId;

        $internetshop->start_at = Carbon::parse($request->start_at);
        // Call
        $internetshop->call_first_received = ($request->call_first_received) ? Carbon::parse($request->call_first_received) : null;
        $internetshop->call_attempts = $request->call_attempts ? (int) $request->call_attempts : 0;
        // Sms
        $internetshop->sms_first_received = ($request->sms_first_received) ? Carbon::parse($request->sms_first_received) : null;
        $internetshop->sms_attempts = $request->sms_attempts ? (int) $request->sms_attempts : 0;
        // Email
        $internetshop->email_first_received = ($request->email_first_received) ? Carbon::parse($request->email_first_received) : null;
        $internetshop->email_attempts = $request->email_attempts ? (int) $request->email_attempts : 0;
        $internetshop->email_second_received = ($request->email_second_received) ? Carbon::parse($request->email_second_received) : null;
        // Chat
        $internetshop->chat_first_received = ($request->chat_first_received) ? Carbon::parse($request->chat_first_received) : null;
        $internetshop->chat_attempts = $request->chat_attempts ? (int) $request->chat_attempts : 0;
        // Verification Screenshots
        $internetshop->verification_screenshot = $this->saveImageAs($request->verificationScreenshot, 'verification-screenshots/', 'webp', 's3');
        $internetshop->verification_screenshot_fallback = $this->saveImageAs($request->verificationScreenshot, 'verification-screenshots/', 'jpg', 's3');

        $internetshop->report_content = isset($request->report_content) ? $request->report_content : '';

        $internetshop->save();

        // Additional Screenshots
        if (isset($request->emailScreenshots) && ! empty($request->emailScreenshots)) {
            $this->emailScreenshotService->store($internetshop->id, collect($request->emailScreenshots));
        }

        // Insert Truecar Fields
        if ((int) $request->dealer_id === 48) {
            $fields = json_decode($request->tcfields, true);
            $truecarfields = new TrueCarField();
            $truecarfields->fill($fields);
            $truecarfields->internet_shop_id = $internetshop->id;

            if (isset($request->tc_recording) && $request->hasFile('tc_recording')) {
                $ext = $request->tc_recording->extension();
                $filename = Str::random(50).'.'.$ext;
                $file = $this->saveRecordings($request->tc_recording, $filename, 'internetshop-recordings/', 's3');
                $truecarfields->recording = $file ? $file['fileName'] : null;
            }

            $truecarfields->date_time_followup = $fields['date_time_followup'] ? Carbon::parse($fields['date_time_followup']) : null;

            $truecarfields->save();

            // Update CSM name for Internet Shop Table
            $internetshop->csm_name = $fields['csm_name'];
            $internetshop->save();
        }

        $this->fetchSms($internetshop);

        return $internetshop;
    }

    //    Unused, function moved to InternetPostController
    public function update($request, $internetshop)
    {
        $internetshop->fill($request->all());
        $internetshop->is_dealer = $request->is_dealer === 'true' ? 1 : 0;

        if ($lead = LeadSource::find($request->lead_source)) {
            $lead_id = $lead->id;
        } else {
            $lead_id = LeadSource::insertOrIgnore(['value' => $request->lead_source])->id;
        }

        $internetshop->lead_source = $lead_id;

        if ($specific_dealer = SpecificDealer::find($request->specific_dealer_id)) {
            $specificDealerId = $specific_dealer->id;
        } else {
            $specificDealerId = SpecificDealer::firstOrCreate(['name' => $request->specific_dealer_id, 'dealer_id' => $request->dealer_id])->id;
        }
        $internetshop->specific_dealer_id = $specificDealerId;

        $competitorId = $request->competitor_id;

        if (isset($competitorId) && $competitorId !== 'null' && $competitorId !== null) {
            if ($competitor = SpecificDealerCompetitor::find($request->competitor_id)) {
                $competitorId = $competitor->id;
            } else {
                $competitorId = SpecificDealerCompetitor::firstOrCreate(['name' => $request->competitor_id, 'specific_dealer_id' => $specificDealerId])->id;
            }
        } else {
            $competitorId = null;
        }

        $internetshop->competitor_id = $competitorId;

        $internetshop->start_at = Carbon::parse($request->start_at);
        // Call
        $internetshop->call_first_received = ($request->call_first_received) ? Carbon::parse($request->call_first_received) : null;
        $internetshop->call_attempts = $request->call_attempts ? (int) $request->call_attempts : 0;
        // Sms
        $internetshop->sms_first_received = ($request->sms_first_received) ? Carbon::parse($request->sms_first_received) : null;
        $internetshop->sms_attempts = $request->sms_attempts ? (int) $request->sms_attempts : 0;
        // Email
        $internetshop->email_first_received = ($request->email_first_received) ? Carbon::parse($request->email_first_received) : null;
        $internetshop->email_attempts = $request->email_attempts ? (int) $request->email_attempts : 0;
        $internetshop->email_second_received = ($request->email_second_received) ? Carbon::parse($request->email_second_received) : null;
        // Chat
        $internetshop->chat_first_received = ($request->chat_first_received) ? Carbon::parse($request->chat_first_received) : null;
        $internetshop->chat_attempts = $request->chat_attempts ? (int) $request->chat_attempts : 0;

        if (isset($request->verificationScreenshot)) {
            $internetshop->verification_screenshot = $this->saveImageAs($request->verificationScreenshot, 'verification-screenshots/', 'webp', 's3');
            $internetshop->verification_screenshot_fallback = $this->saveImageAs($request->verificationScreenshot, 'verification-screenshots/', 'jpg', 's3');
        }

        if (! empty($request->existingChatScreenshots)) {
            $existingChatScreenshots = $request->existingChatScreenshots;
            $internetshop->chatScreenshots()->whereNotIn('id', $existingChatScreenshots)->delete();
        }
        $request->existingEmailScreenshots = json_decode($request->existingEmailScreenshots);
        if (! empty($request->existingEmailScreenshots)) {
            $existingEmailScreenshots = $request->existingEmailScreenshots;
            $internetshop->emailScreenshots()->whereNotIn('id', $existingEmailScreenshots)->delete();
        }

        $internetshop->emailScreenshots()->update(['internet_shop_id' => null]);
        if (isset($request->emailScreenshots) && ! empty($request->emailScreenshots)) {
            $this->emailScreenshotService->update($internetshop->id, collect($request->emailScreenshots));
        }

        if (isset($request->chatScreenshots) && ! empty($request->chatScreenshots)) {
            $chatScreenshots = isset($request->chatScreenshots) ? collect($request->chatScreenshots) : collect([]);
        }

        // report content
        $internetshop->report_content = isset($request->report_content) ? $request->report_content : '';

        $internetshop->save();

        if ((int) $request->dealer_id === 48) {
            $fields = json_decode($request->tcfields, true);
            $truecarfields = TrueCarField::where('internet_shop_id', $internetshop->id)->first();
            if (! $truecarfields) {
                $truecarfields = new TrueCarField();
                $truecarfields->internet_shop_id = $internetshop->id;
            }
            $truecarfields->fill($fields);

            $truecarfields->date_time_followup = $fields['date_time_followup'] ? Carbon::parse($fields['date_time_followup']) : null;

            if ($request->hasFile('tc_recording')) {
                $ext = $request->tc_recording->extension();
                $filename = Str::random(50).'.'.$ext;
                $file = $this->saveRecordings($request->tc_recording, $filename, 'internetshop-recordings/', 's3');
                $truecarfields->recording = $file ? $file['fileName'] : null;
            }

            $truecarfields->save();

            // Update CSM name for Internet Shop Table
            $internetshop->csm_name = $fields['csm_name'];
            $internetshop->save();
        }

        return $internetshop;
    }

    public function fetchSms($shop)
    {
        try {
            $client = new Client(
                config('twilio.sid'),
                config('twilio.token')
            );
        } catch (ConfigurationException $e) {
            Log::critical('Twilio Credentials is not valid'.$e->getMessage());
        }

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
