<?php

namespace App\Services;

use App\Models\FeaturedVideo;
use Carbon\Carbon;

class GetVideoOfTheDayService
{
    public $featuredVideo;

    public function __construct(FeaturedVideo $featuredVideo)
    {
        $this->featuredVideo = $featuredVideo;
    }

    public function index()
    {
        /*
         * Reference for the carbon
         * https://laracasts.com/discuss/channels/laravel/where-does-carbon-class-take-timezone-from?page=1
         */
        return $this->featuredVideo
            ->select([
                'id', 'title', 'url',
            ])
            ->where('start_at', '<=', Carbon::now()->tz('America/New_York'))
            ->where('end_at', '>=', Carbon::now()->tz('America/New_York'))
            ->first();
    }
}
