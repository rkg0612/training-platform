<?php

namespace App\Services\PhoneShop;

use App\Helpers\FilterRecords;
use App\Http\Resources\PhoneShopCollection;
use App\Jobs\RemoveFileAtS3;
use App\Jobs\RemoveParentDirectory;
use App\Jobs\RemovePhoneShopFolder;
use App\Models\City;
use App\Models\CompetitorRecording;
use App\Models\DealerRecording;
use App\Models\PhoneShop;
use App\Models\ShopSpecificDealer;
use App\Models\SpecificDealer;
use App\Services\PhoneShop\CompetitorRecordingService;
use App\Services\PhoneShop\DealerRecordingService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class PhoneShopService
{
    private $phoneShop;

    private $purifierConfig;

    private $dealerRecordingService;

    private $competitorRecordingService;

    public function __construct(PhoneShop $phoneShop, DealerRecordingService $dealerRecordingService, CompetitorRecordingService $competitorRecordingService)
    {
        $this->phoneShop = $phoneShop;
        $this->purifierConfig = [
            'HTML.Allowed' =>'table[class|style|align|cellpadding|cellspacing|border],tbody[style|class],tr[style|class|width|valign|height],td[style|class|width|valign|height],br,p[class|style],a[href|target]',
            'Attr.AllowedFrameTargets' => ['_blank'],
        ];
        $this->dealerRecordingService = $dealerRecordingService;
        $this->competitorRecordingService = $competitorRecordingService;
    }

    public function index($request)
    {
        $phoneShop = $this->setType($request['type'], $request['search']);

        $sortDesc = $request['sortDesc'] ? 'asc' : 'desc';

        if ($request['isGroupShop']) {
            return $this->groupShopQuery($request['search']);
        }

        if ($request['sortBy'] === 'undefined') {
            $phoneShop = $phoneShop->orderBy('created_at', $sortDesc);
        } elseif ($request['sortBy'] === 'specific_dealer.name') {
            $phoneShop = $phoneShop->orderBySpecificD.ealerName($sortDesc);
        } elseif ($request['sortBy'] === 'dealer') {
            $phoneShop = $phoneShop->orderByDealerName($sortDesc);
        } elseif ($request['sortBy'] === 'secret_shopper.name') {
            $phoneShop = $phoneShop->orderBySecretShopperName($sortDesc);
        } else {
            $phoneShop = $phoneShop->orderBy('created_at', $sortDesc);
        }

        return new PhoneShopCollection($phoneShop->paginate($request['perPage'] ?: 5));
    }

    public function groupShopQuery($search)
    {
        $user = auth()->user();
        $phoneShop = PhoneShop::with(['dealer', 'specificDealer'])
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

        return new PhoneShopCollection($phoneShop);
    }

    public function setType($type, $search)
    {
        $type = is_array($type) ? collect($type) : collect(explode(',', $type));

        $relationships = ['dealerRecordings', 'competitorRecordings'];
        $searchColumns = ['secretShopper.name', 'specificDealer.name', 'dealer.name', 'created_at'];

        return FilterRecords::make($this->phoneShop, $type, $relationships, $searchColumns, $search)->get();
    }

    public function store($fields, $files)
    {
        $phoneShop = PhoneShop::create([
            'guest_view_id' => Str::uuid(),
            'dealer_id' => $fields['dealer_id'],
            'specific_dealer_id' => SpecificDealer::firstOrCreate([
                'dealer_id' => $fields['dealer_id'],
                'name' => $fields['specific_dealer_id'],
            ])->id,
            'state_id' => $fields['state_id'],
            'zip_code' => $fields['zip_code'],
            'city_id' => City::firstOrCreate([
                'state_id' => $fields['state_id'],
                'value' => $fields['city'],
            ])->id,
            'shop_type' => $fields['shop_type'],
            'sales_person_name' => $fields['sales_person_name'],
            'start_date' => Carbon::parse($fields['start_date']),
            'inbound_call_grade' => $fields['inbound_call_grade'],
            'lead_name' => $fields['lead_name'],
            'secret_shopper_id' => $fields['secret_shopper_id'],
        ]);

        $this->checkForFiles($files, $phoneShop->id);

        $this->removeRootFolder("public/phone_shops/{$phoneShop->id}");

        return [
            'success' => true,
        ];
    }

    public function destroy($id)
    {
        if ($this->phoneShop->find($id)->delete()) {
            return [
                'success' => true,
            ];
        }
        abort(500);
    }

    public function updateGuestViewValueIfNull($id): void
    {
        $phoneShop = PhoneShop::find($id);

        if ($phoneShop->guest_view_id) {
            return;
        }

        $phoneShop->guest_view_id = Str::uuid();
        $phoneShop->save();
    }

    public function update($fields, $files)
    {
        $this->updateGuestViewValueIfNull($fields['id']);

        $this->phoneShop->find($fields['id'])->update([
            'dealer_id' => $fields['dealer_id'],
            'specific_dealer_id' => SpecificDealer::firstOrCreate([
                'dealer_id' => $fields['dealer_id'],
                'name' => $fields['specific_dealer_id'],
            ])->id,
            'state_id' => $fields['state_id'],
            'zip_code' => $fields['zip_code'],
            'city_id' => City::firstOrCreate([
                'state_id' => $fields['state_id'],
                'value' => $fields['city'],
            ])->id,
            'shop_type' => $fields['shop_type'],
            'sales_person_name' => $fields['sales_person_name'],
            'start_date' => Carbon::parse($fields['start_date']),
            'inbound_call_grade' => $fields['inbound_call_grade'],
            'lead_name' => $fields['lead_name'],
            'secret_shopper_id' => $fields['secret_shopper_id'],
        ]);

        $this->checkForFiles($files, $fields['id']);

        if (isset($fields['dealerRecordingsRemoved'])) {
            $this->processFileDeletion(collect($fields['dealerRecordingsRemoved']), $fields['id'], true);
        }

        if (isset($fields['competitorRecordingsRemoved'])) {
            $this->processFileDeletion(collect($fields['competitorRecordingsRemoved']), $fields['id'], false);
        }

        $path = 'phone_shops/'.$fields['id'];
        $disk = 's3';

        RemoveParentDirectory::dispatch($path, $disk)
            ->delay(now()->addMinutes(2));

        return [
            'success' => true,
        ];
    }

    private function processFileDeletion($recordings, $id, bool $isDealer = false)
    {
        $recordings->each(function ($recording) use ($id, $isDealer) {
            if ($isDealer) {
                $row = DealerRecording::query()
                    ->where('label', $recording)
                    ->where('phone_shop_id', $id)
                    ->first();

                $path = 'phone_shops/'.$id.'/dealer-recordings/';
            }

            if (! $isDealer) {
                $row = CompetitorRecording::query()
                    ->where('label', $recording)
                    ->where('phone_shop_id', $id)
                    ->first();

                $path = 'phone_shops/'.$id.'/competitor-recordings/';
            }

            if (! $row) {
                return;
            }

            RemoveFileAtS3::dispatchNow($path.'/'.$row->value);

            $row->forceDelete();
        });
    }

    private function checkForFiles($files, $id)
    {
        if (isset($files['dealerRecordings'])) {
            $dealerRecordings = Collection::make($files['dealerRecordings']);
            $this->dealerRecordingService->store($id, $dealerRecordings);
        }

        if (isset($files['competitorRecordings'])) {
            $competitorRecordings = Collection::make($files['competitorRecordings']);
            $this->competitorRecordingService->store($id, $competitorRecordings);
        }
    }

    public function show($id)
    {
        return $this->phoneShop->with([
            'dealer', 'specificDealer', 'state', 'city', 'dealerRecordings', 'competitorRecordings', 'secretShopper',
        ])->find($id);
    }

    private function removeRootFolder($path)
    {
        dispatch(
            new RemovePhoneShopFolder($path)
        )->delay(now()->addMinutes(6));
    }
}
