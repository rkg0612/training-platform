<?php

namespace App\Services\VideoOfTheDay;

use App\Models\FeaturedVideo;
use App\Models\FeaturedVideoRelatedUnit;
use App\Models\UserUnit;
use App\Models\UserWatchedFeaturedVideo;

class FeaturedVideoMarkAsWatchService
{
    public function isFeaturedVideoWatched($featuredVideoId)
    {
        return UserWatchedFeaturedVideo::where('featured_video_id', $featuredVideoId)
            ->where('user_id', auth()->user()->id)
            ->first();
    }

    public function markAsWatched($featuredVideoId)
    {
        $watched = UserWatchedFeaturedVideo::where('user_id', auth()->user()->id)
            ->where('featured_video_id', $featuredVideoId)
            ->first();

        if (! $watched) {
            UserWatchedFeaturedVideo::create([
                'user_id' => auth()->user()->id,
                'featured_video_id' => $featuredVideoId,
                'watched'   =>  true,
            ]);
        } else {
            $watched->watched = true;
            $watched->save();
        }

        $unitIds = FeaturedVideoRelatedUnit::where('featured_video_id', $featuredVideoId)->pluck('unit_id');

        $unitIds->each(function ($unitId) {
            $userUnit = UserUnit::where('user_id', auth()->user()->id)
                ->where('unit_id', $unitId)
                ->first();

            if (! is_null($userUnit)) {
                return $this->updateUserUnit($userUnit->id);
            }

            return UserUnit::create([
                'user_id' => auth()->user()->id,
                'unit_id' => $unitId,
                'played'    =>  true,
                'date_completed' => now(),
            ]);
        });

        return true;
    }

    public function playedVideo($featuredVideoId)
    {
        $watched = UserWatchedFeaturedVideo::where('user_id', auth()->user()->id)
            ->where('featured_video_id', $featuredVideoId)
            ->first();

        if (! $watched) {
            UserWatchedFeaturedVideo::create([
                'user_id' => auth()->user()->id,
                'featured_video_id' => $featuredVideoId,
                'played'   =>  true,
            ]);
        }

        $unitIds = FeaturedVideoRelatedUnit::where('featured_video_id', $featuredVideoId)->pluck('unit_id');

        $unitIds->each(function ($unitId) {
            $userUnit = UserUnit::where('user_id', auth()->user()->id)
                ->where('unit_id', $unitId)
                ->first();

            if (! is_null($userUnit)) {
                $userUnit = UserUnit::find($userUnitId);
                if (is_null($userUnit->played)) {
                    $userUnit->played = true;
                    $userUnit->save();
                }

                return $userUnit;
            }

            return UserUnit::create([
                'user_id' => auth()->user()->id,
                'unit_id' => $unitId,
                'played'    =>  true,
            ]);
        });

        return true;
    }

    private function updateUserUnit($userUnitId)
    {
        $userUnit = UserUnit::find($userUnitId);
        if (is_null($userUnit->date_completed)) {
            $userUnit->date_completed = now();
            $userUnit->played = true;
            $userUnit->save();
        }

        return $userUnit;
    }
}
