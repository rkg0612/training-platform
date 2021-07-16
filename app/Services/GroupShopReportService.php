<?php

namespace App\Services;

use App\Mail\GroupShopReportPreview;
use App\Models\GroupShop;
use Carbon\Carbon;

class GroupShopReportService
{
    private $dealerOptions;

    private $textColors = [
        'red' => '#C62828',
        'green' => '#43A047',
        'yellow' => '#F9A825',
    ];

    public function getPreviewReport($id)
    {
        $groupShop = GroupShop::with([
            'specificDealer',
            'dealer.options',
            'internetShops.specificDealer',
            'internetShops.competitor',
            'phoneShops.specificDealer',
        ])
            ->where('id', $id)
            ->first();

        if (empty($groupShop)) {
            abort(404);
        }

        $logoImage = $this->getDealerLogo($groupShop->dealer->options);

        $dealerOptions = $groupShop->dealer->options->map(function ($query) {
            return [
                $query->name => $this->processOptionValue($query->name, $query->value),
            ];
        })->collapse()->toArray();
        $this->dealerOptions = $groupShop->dealer->options;

        $internetShop = $this->processInternetShop($groupShop);
        $phoneShop = $groupShop->phoneShops->first();

        $bgColor = $dealerOptions['logo_bg_color'] ?? '#222222';

        return (new GroupShopReportPreview(
            $groupShop,
            $logoImage,
            $dealerOptions,
            $internetShop,
            $phoneShop,
            $bgColor
        ))->render();
    }

    public function sendReport($template)
    {
    }

    /**
     * Get the dealer's logo image.
     * @param $options
     * @return string
     */
    private function getDealerLogo($options)
    {
        $logo = $options->filter(function ($query) {
            return $query->name === 'logo_image';
        })
            ->first();

        return ! empty($logo) ? $logo->value : 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/main-logo.png';
    }

    /**
     * Process the option's value so it can handle time values.
     * @param $name
     * @param $value
     * @return mixed
     */
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

    /**
     * @param $groupShop
     * @return array[]
     */
    private function processInternetShop($groupShop)
    {
        $result = [
            'response' => [],
            'attempt' => [],
            'additionalDetails' => [],
        ];

        foreach ($groupShop->internetShops as $internetShop) {
            $dealerName = $internetShop->is_dealer ? $internetShop->specificDealer->name : $internetShop->competitor->name;

            $result['response'][$dealerName] = [
                'email' => [
                    'value' => empty($internetShop->email_response_time) ? '--:--:--' : $internetShop->email_response_time,
                    'color' => $this->getResponseTimeColorRating($internetShop, 'email'),
                ],
                'call' => [
                    'value' => empty($internetShop->call_response_time) ? '--:--:--' : $internetShop->call_response_time,
                    'color' => $this->getResponseTimeColorRating($internetShop, 'call'),
                ],
                'sms' => [
                    'value' => empty($internetShop->sms_response_time) ? '--:--:--' : $internetShop->sms_response_time,
                    'color' => $this->getResponseTimeColorRating($internetShop, 'sms'),
                ],
            ];

            $result['attempt'][$dealerName] = [
                'email' => [
                    'value' => empty($internetShop->email_attempts) ? 'N/A' : $internetShop->email_attempts,
                    'color' => $this->getAttemptColorRating($internetShop, 'email'),
                ],
                'call' => [
                    'value' => empty($internetShop->call_attempts) ? 'N/A' : $internetShop->call_attempts,
                    'color' => $this->getAttemptColorRating($internetShop, 'call'),
                ],
                'sms' => [
                    'value' => empty($internetShop->sms_attempts) ? 'N/A' : $internetShop->sms_attempts,
                    'color' => $this->getAttemptColorRating($internetShop, 'sms'),
                ],
            ];

            $date = Carbon::parse($internetShop->start_at);

            $result['additionalDetails'][$dealerName] = [
                'makeAndModel' => "{$internetShop->make} {$internetShop->model}",
                'leadName' => $internetShop->lead_name,
                'date' => $date->setTimezone($internetShop->timezone)->format('m/d/Y h:iA'),
                'salesperson' => $internetShop->salesperson_name,
            ];
        }

        return $result;
    }

    /**
     * Method for handling the internet response color rating.
     * @param $internetShop
     * @param $type
     * @return string
     */
    private function getResponseTimeColorRating($internetShop, $type)
    {
        $value = $internetShop->{"{$type}_response_time"};

        if (empty($value)) {
            return $this->textColors['red'];
        }

        $yellowOption = $this->dealerOptions->filter(function ($query) use ($type) {
            return $query->name == "{$type}_yellow_response_time";
        })
            ->first()
            ->value;
        $redOption = $this->dealerOptions->filter(function ($query) use ($type) {
            return $query->name == "{$type}_red_response_time";
        })
            ->first()
            ->value;
        $dealerYellowOptionDate = $this->parseTimeToDate($yellowOption);
        $dealerRedOptionDate = $this->parseTimeToDate($redOption);
        $valueDate = $this->parseTimeToDate($value);

        if ($valueDate->greaterThanOrEqualTo($dealerRedOptionDate)) {
            return $this->textColors['red'];
        }

        if ($valueDate->lessThan($dealerYellowOptionDate)) {
            return $this->textColors['green'];
        }

        return $this->textColors['yellow'];
    }

    /**
     * Method for handling the internet attempt color rating.
     * @param $internetShop
     * @param $type
     * @return string
     */
    private function getAttemptColorRating($internetShop, $type)
    {
        $value = $internetShop->{"{$type}_attempts"};

        if (empty($value)) {
            return $this->textColors['red'];
        }

        $yellowOption = $this->dealerOptions->filter(function ($query) use ($type) {
            return $query->name == "{$type}_yellow_attempts";
        })->first();

        $redOption = $this->dealerOptions->filter(function ($query) use ($type) {
            return $query->name == "{$type}_red_attempts";
        })->first();

        $greenOption = $this->dealerOptions->filter(function ($query) use ($type) {
            return $query->name == "{$type}_green_attempts";
        })->first();

        if (empty($redOption->value) || empty($yellowOption->value)) {
            $redOption = $this->defaultValueAttempt($type, 'red');
            $yellowOption = $this->defaultValueAttempt($type, 'yellow');
            $greenOption = $this->defaultValueAttempt($type, 'yellow');
        } else {
            $redOption = $redOption->value;
            $yellowOption = $yellowOption->value;
            $greenOption = $greenOption->value;
        }

        if ($value <= $redOption && $value < $yellowOption) {
            return $this->textColors['red'];
        }

        if ($value <= $yellowOption && $value < $greenOption) {
            return $this->textColors['yellow'];
        }

        return $this->textColors['green'];
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

    private function defaultValueAttempt($type, $color)
    {
        $lookup = [
            'email' => [
                'red' => 1,
                'yellow' => 3,
                'green' => 4,
            ],
            'call' => [
                'red' => 1,
                'yellow' => 3,
                'green' => 4,
            ],
            'sms' => [
                'red' => 1,
                'yellow' => 3,
                'green' => 4,
            ],
        ];

        return $lookup[$type][$color];
    }
}
