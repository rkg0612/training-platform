<?php

namespace App\Services\InternetShop;

use App\Mail\PreviewInternetShopReport;
use App\Models\DealerOption;
use App\Models\InternetShop;
use App\Models\SpecificDealer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PreviewInternetShopReportService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function show($id)
    {
        $internetShop = $this->internetShop->with('truecar_fields')->findOrFail($id);

        $image = DealerOption::query()
            ->where('name', 'logo_image')
            ->where('dealer_id', $internetShop->dealer_id)
            ->first();

        $logoBgColor = DealerOption::query()
            ->where('name', 'logo_bg_color')
            ->where('dealer_id', $internetShop->dealer_id)
            ->first();

        if (is_null($image)) {
            $image = 'https://webinarinc-app.s3-us-west-1.amazonaws.com/images/image-a715ffa0-d943-11e9-b53a-43d0016f9823.png';
        } else {
            $image = $image->value;
        }

        if (is_null($logoBgColor)) {
            $logoBgColor = '#4C586A';
        } else {
            $logoBgColor = $logoBgColor->value;
        }

        $nationalAverage = [];
        if ($internetShop->dealer_id === 48) {
            $internetshops = InternetShop::where('dealer_id', 48)
                ->whereBetween('start_at', [$internetShop->start_at->firstOfMonth(), $internetShop->start_at->endOfMonth()])
                ->get();
            $nationalAverage = [
                'email' =>  $this->getData($internetshops, 'email'),
                'sms' =>  $this->getData($internetshops, 'sms'),
                'call' =>  $this->getData($internetshops, 'call'),
            ];
        }

        $shop = $internetShop;
        $dealerOptions = $shop->dealer->options->map(function ($query) {
            return [
                $query->name => $this->processOptionValue($query->name, $query->value),
            ];
        })->collapse()->toArray();

        return (new PreviewInternetShopReport($shop, $dealerOptions, $image, $logoBgColor, $nationalAverage))->render();
    }

    private function tenPercent($count): int
    {
        $count = $count * .10;

        return ceil($count);
    }

    private function getData($internetShop, $column)
    {
        $columnAttempts = $column.'_attempts';
        $columnResponseTime = $column.'_response_time';

        $tenPercentC = $this->tenPercent($internetShop->count());

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
        $tenPercent = $this->tenPercent($responseTimeResults->count());

        $responseTimeResultsTop = $responseTimeResults->sort();
        $responseTimeResultsBtm = $responseTimeResults->sort()->reverse();
        $responseTimeResultsTop = $responseTimeResultsTop->take($tenPercent);
        $responseTimeResultsBtm = $responseTimeResultsBtm->take($tenPercent);

        $tenPercent = $tenPercentC;
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

    private function processResponseTime(Collection $value): Collection
    {
        $responseTimes = $value->filter();
        $responseTimes->transform(function ($item) {
            return $this->parseTimeToDate($item);
        });

        return $responseTimes;
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

    private function getAverage(int $time, int $count)
    {
        if (empty($time)) {
            return $time;
        }

        return $time / $count;
    }

    private function processOptionValue($name, $value)
    {
        // if option is not a response time, then just return the value immediately
        if (strpos($name, 'response_time') === false) {
            return $value;
        }

        $time = $this->parseTimeToDate($value);

        if ($time->hour === 0) {
            return "{$time->minute} minutes";
        }

        $dateNow = now()->startOfDay();
        $hours = $dateNow->diffInHours($time);

        return "{$hours} hours".' and '."{$time->minute} minutes";
    }

    private function parseTimeToDate($time)
    {
        $date = now()->startOfDay();
        $timeArray = explode(':', $time);

        return $date
            ->addHours($timeArray[0])
            ->minutes($timeArray[1]);
    }
}
