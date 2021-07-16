<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreviewPhoneShopReport extends Mailable
{
    use Queueable, SerializesModels;

    public $inboundCallGrade;
    public $specificDealer;
    public $shopDate;
    public $image;
    public $link;
    public $bgColor;

    /**
     * Create a new message instance.
     *
     * @param $inboundCallGrade
     * @param $specificDealer
     * @param $shopDate
     * @param $image
     * @param $link
     * @param $bgColor
     */
    public function __construct($inboundCallGrade, $specificDealer, $shopDate, $image, $link, $bgColor)
    {
        $this->inboundCallGrade = $inboundCallGrade;
        $this->specificDealer = $specificDealer;
        $this->shopDate = $shopDate;
        $this->image = $image;
        $this->link = $link;
        $this->bgColor = $bgColor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mailers.phoneshopreport')
            ->with([
                'image' => $this->image,
                'specificDealer' => $this->specificDealer,
                'shopDate' => $this->shopDate,
                'inbound_call_grade' => $this->inboundCallGrade,
                'link' => $this->link,
                'bgColor' => $this->bgColor,
            ]);
    }
}
