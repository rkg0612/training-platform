<?php

namespace App\Services\SecretShopDataSummary;

use App\Helpers\SecretShopSummaryReport;
use App\Models\InternetShop;

class SecretShopDataSummaryService
{
    public $internetShop = null;

    private $secretShopHelper;

    public function __construct(SecretShopSummaryReport $secretShopSummaryReport)
    {
        $this->secretShopHelper = $secretShopSummaryReport;
    }

    public function index($request)
    {
        $request = $this->secretShopHelper->removeNull($request);

        $this->internetShop = InternetShop::getRecordsBasedOnParameters($this->secretShopHelper->createParams($request));

        if (! $this->internetShop->count()) {
            abort(404, 'No Record Found!');
        }
        $tenPercent = intval($this->secretShopHelper->computeTenPercent($this->internetShop->count()));

        return response()->json([
            'summaries' => $this->summaries($tenPercent),
        ]);
    }

    public function show(): array
    {
        $internetShop = InternetShop::where('dealer_id', auth()->user()->dealer_id);
        $this->internetShop = $internetShop->get();
        $tenPercent = intval($internetShop->count());

        return [
            'summaries' => [
                (object) [
                    'title' => 'Call',
                    'icon' => 'fal fa-phone-rotary',
                    'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'call_response_time', true, $tenPercent),
                    'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'call_attempts', true, $tenPercent),
                ],
                (object) [
                    'title' => 'Email',
                    'icon' => 'fal fa-envelope',
                    'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'email_response_time', true, $tenPercent),
                    'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'email_attempts', true, $tenPercent),
                ],
                (object) [
                    'title' => 'Chat',
                    'icon' => 'fal fa-comment-dots',
                    'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'chat_response_time', true, $tenPercent),
                    'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'chat_attempts', true, $tenPercent),
                ],
                (object) [
                    'title' => 'SMS',
                    'icon' => 'fal fa-sms',
                    'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'sms_response_time', true, $tenPercent),
                    'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'sms_attempts', true, $tenPercent),
                ],
            ],
        ];
    }

    public function summaries($tenPercent): array
    {
        return [
            [
                'title' => 'Call',
                'icon' => 'fal fa-phone-rotary',
                'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'call_response_time', true, $tenPercent),
                'bottom_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'call_response_time', false, $tenPercent),
                'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'call_attempts', true, $tenPercent),
                'bottom_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'call_attempts', false, $tenPercent),
            ],
            [
                'title' => 'Email',
                'icon' => 'fal fa-envelope',
                'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'email_response_time', true, $tenPercent),
                'bottom_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'email_response_time', false, $tenPercent),
                'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'email_attempts', true, $tenPercent),
                'bottom_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'email_attempts', false, $tenPercent),
            ],
            [
                'title' => 'Chat',
                'icon' => 'fal fa-comment-dots',
                'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'chat_response_time', true, $tenPercent),
                'bottom_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'chat_response_time', false, $tenPercent),
                'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'chat_attempts', true, $tenPercent),
                'bottom_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'chat_attempts', false, $tenPercent),
            ],
            [
                'title' => 'SMS',
                'icon' => 'fal fa-sms',
                'top_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'sms_response_time', true, $tenPercent),
                'bottom_response_time' => $this->secretShopHelper->getAverageResponseTime($this->internetShop, 'sms_response_time', false, $tenPercent),
                'top_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'sms_attempts', true, $tenPercent),
                'bottom_attempts' => $this->secretShopHelper->getAverageAttempts($this->internetShop, 'sms_attempts', false, $tenPercent),
            ],
        ];
    }
}
