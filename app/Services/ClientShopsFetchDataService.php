<?php

namespace App\Services;

use App\Exports\ReportExport\SecretShopsReport;
use App\Helpers\ClientShopsHelper;
use App\Models\InternetShop;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ClientShopsFetchDataService
{
    private $user;
    private $clientShopsHelper;

    public function __construct(ClientShopsHelper $clientShopsHelper)
    {
        $this->user = auth()->user();
        $this->clientShopsHelper = $clientShopsHelper;
    }

    public function index($request)
    {
        $is = InternetShop::with('dealer', 'specificDealer', 'city', 'state', 'postedBy', 'source');
        $is = $this->user->specific_dealer_id ? $is->where('specific_dealer_id', $this->user->specific_dealer_id) : $is->where('dealer_id', $this->user->dealer_id);

        $internetShop = $this->clientShopsHelper->builderQuery($is, $request->all());
        $internetShop = $internetShop->get();

        return [
            'internetshops'    =>  $internetShop,
            'datasummary'  =>  [
                [
                    'title' => 'Call',
                    'icon' => 'fal fa-phone-rotary',
                    'data'  =>  $this->getData($internetShop, 'call', $request->noFilter),
                ],
                [
                    'title' => 'Email',
                    'icon' => 'fal fa-envelope',
                    'data'  =>  $this->getData($internetShop, 'email', $request->noFilter),
                ],
                [
                    'title' => 'Chat',
                    'icon' => 'fal fa-comment-dots',
                    'data'  =>  $this->getData($internetShop, 'chat', $request->noFilter),
                ],
                [
                    'title' => 'SMS',
                    'icon' => 'fal fa-sms',
                    'data'  =>  $this->getData($internetShop, 'sms', $request->noFilter),
                ],
            ],
        ];
    }

    public function exportData($request)
    {
        if (! $request->has('internetshops')) {
            return false;
        }

        $export = new SecretShopsReport($request->internetshops);
        $filename = Carbon::now()->format('Ymdhms').'-'.$this->user->id.'.xlsx';
        if (($export)->store('export/reports/'.$filename, 's3', null, ['visibility' =>  'public'])) {
            return response()->json([
                'success'   =>  true,
                'filepath'  =>  Storage::disk('s3')->url('export/reports/'.$filename),
            ]);
        }
    }

    private function tenPercent($count): int
    {
        $count = $count * .10;

        return ceil($count);
    }

    private function averageTime(Collection $value): string
    {
        if ($value->isEmpty()) {
            return '--:--:--';
        }

        $referenceDate = now()->startOfDay();
        $totalHours = 0;
        $totalMinutes = 0;
        $totalSeconds = 0;
        $count = $value->count();

        foreach ($value as $item) {
            $totalHours += $referenceDate->diffInHours($item);
            $totalMinutes += $referenceDate->diffInMinutes($item);
            $totalSeconds += $referenceDate->diffInSeconds($item);
        }

        if (empty($totalHours) && empty($totalMinutes) && empty($totalSeconds)) {
            return '00:00:00';
        }

        $totalHours = $this->getAverage($totalHours, $count);
        $totalMinutes = $this->getAverage($totalMinutes, $count);
        $totalSeconds = $this->getAverage($totalSeconds, $count);

        return $referenceDate
            ->addHours($totalHours / $count)
            ->addMinutes($totalMinutes / $count)
            ->addSeconds($totalSeconds / $count)
            ->format('H:i:s');
    }

    private function getData($internetShop, $column, $noFilter)
    {
        $columnAttempts = $column.'_attempts';
        $columnResponseTime = $column.'_response_time';

        $tenPercentC = ! $noFilter ? $this->tenPercent($internetShop->count()) : $internetShop->count();

        $attemptResults = $internetShop->pluck($columnAttempts);

        $attemptResultsTop = $attemptResults->sort()->reverse();
        $attemptResultsBtm = $attemptResults->sort();
        $attemptResultsTop = $attemptResultsTop->reject(function ($attempts) {
            return $attempts === 0;
        })->take($tenPercentC)->sum();

        $attemptResultsBtm = $attemptResultsBtm->reject(function ($attempts) {
            return $attempts === 0;
        })->take($tenPercentC)->sum();

        $responseTimeResults = $internetShop->pluck($columnResponseTime);
        $responseTimeResults = $this->processResponseTime($responseTimeResults);
        $tenPercent = ! $noFilter ? $this->tenPercent($responseTimeResults->count()) : $responseTimeResults->count();

        $responseTimeResultsTop = $responseTimeResults->sort();
        $responseTimeResultsBtm = $responseTimeResults->sort()->reverse();
        $responseTimeResultsTop = $responseTimeResultsTop->take($tenPercent);
        $responseTimeResultsBtm = $responseTimeResultsBtm->take($tenPercent);

        $tenPercent = ! $noFilter ? $tenPercentC : $internetShop->count();
        $attemptResultsTop = $attemptResultsTop !== 0 ? intval(ceil($attemptResultsTop / $tenPercent)) : 0;
        $attemptResultsBtm = $attemptResultsBtm !== 0 ? intval(ceil($attemptResultsBtm / $tenPercent)) : 0;

        $avgTop = $this->averageTime($responseTimeResultsTop);
        $avgBtm = $this->averageTime($responseTimeResultsBtm);

        return [
            'top_attempts'  =>  $attemptResultsTop,
            'bottom_attempts'   =>  $attemptResultsBtm,
            'top_response_time' =>  $avgTop,
            'bottom_response_time'  =>  $avgBtm,
            'tpc'   =>  $tenPercentC,
            'tp'    =>  $tenPercent,
        ];
    }

    /**
     * Parses the $time to a Carbon instance.
     * @param $time
     * @return Carbon
     */
    private function parseTimeToDate($time)
    {
        $date = now()->startOfDay();
        $timeArray = explode(':', $time);

        return $date
            ->addHours($timeArray[0])
            ->minutes($timeArray[1]);
    }

    private function processResponseTime(Collection $value): Collection
    {
        $responseTimes = $value->filter();
        $responseTimes->transform(function ($item) {
            return $this->parseTimeToDate($item);
        });

        return $responseTimes;
    }

    private function getAverage(int $time, int $count)
    {
        if (empty($time)) {
            return $time;
        }

        return $time / $count;
    }
}
