<?php

namespace App\Services;

use App\Models\Event;
use DateTime;
use Spatie\CalendarLinks\Link;

class SyncCalendarService
{
    private $url;

    public function generate($id, $type)
    {
        $event = Event::find($id);

        $link = Link::create($event->name, $event->start_at, $event->end_at)
            ->description($event->url);

        $url = $this->getCalendarUrl($link, $type);

        return response()->json([
            'url' => $url,
        ], 200);
    }

    private function getCalendarUrl(Link $link, $type)
    {
        if ($type === 'gcal') {
            return $link->google();
        }

        if ($type === 'owa' || $type === 'icloud') {
            return $link->ics();
        }
    }
}
