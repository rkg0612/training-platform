<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreviewInternetShopReport extends Mailable
{
    use Queueable, SerializesModels;

    public $shop;
    public $dealerOptions;
    public $image;
    public $bgColor;
    public $nationalAverage;

    /**
     * Create a new message instance.
     *
     * @param $rows
     */
    public function __construct($shop, $dealerOptions, $image, $logoBgColor, $nationalAverage)
    {
        $this->shop = $shop;
        $this->dealerOptions = $dealerOptions;
        $this->image = $image;
        $this->bgColor = $logoBgColor;
        $this->nationalAverage = $nationalAverage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontendBaseUrl = env('FRONTEND_BASE_URL', 'https://app.webinarinc.com');
        $detailedReportLink = $frontendBaseUrl.'/client/internet-shop/'.$this->shop->id;

        return $this->view('mailers.internetshopreport')
            ->with([
                'shop' => $this->shop,
                'dealerOptions' => $this->dealerOptions,
                'image' => $this->image,
                'bgColor' => $this->bgColor,
                'detailedReportLink' => $detailedReportLink,
                'nationalAverage' => $this->nationalAverage,
            ]);
    }
}
