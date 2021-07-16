<?php

namespace App\Mail;

use App\Models\GroupShop;
use App\Models\SpecificDealer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupShopReportPreview extends Mailable
{
    use Queueable, SerializesModels;

    public $groupShop;

    public $image;

    public $dealerOptions;

    public $internetShop;

    public $phoneShop;

    public $bgColor;

    /**
     * Create a new message instance.
     *
     * @param $groupShop
     * @param $logoImage
     * @param $dealerOptions
     * @param $internetShop
     */
    public function __construct($groupShop, $logoImage, $dealerOptions, $internetShop, $phoneShop, $bgColor)
    {
        $this->groupShop = $groupShop;
        $this->image = $logoImage;
        $this->dealerOptions = $dealerOptions;
        $this->internetShop = $internetShop;
        $this->phoneShop = $phoneShop;
        $this->bgColor = $bgColor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mailers.groupshopreport');
    }
}
