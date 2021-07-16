<?php

namespace App\Services\VideoOfTheDay;

use App\Models\FeaturedVideoRelatedUnit;
use App\Models\Unit;
use Illuminate\Support\Collection;

class FeaturedVideoRelatedUnitService
{
    public function index($request)
    {
        return Unit::where('name', 'LIKE', '%'.$request->search.'%')->get();
    }

    public function store($featuredVideoId, $relatedUnits)
    {
        $relatedUnits = Collection::make($relatedUnits);

        $relatedUnits->each(function ($unitId) use ($featuredVideoId) {
            FeaturedVideoRelatedUnit::create([
                'unit_id' => $unitId,
                'featured_video_id' => $featuredVideoId,
            ]);
        });
    }

    public function update($featureVideoId, $units)
    {
        FeaturedVideoRelatedUnit::where('featured_video_id', $featureVideoId)->delete();

        collect($units)->each(function ($unit) use ($featureVideoId) {
            FeaturedVideoRelatedUnit::create([
                'unit_id' => $unit,
                'featured_video_id' => $featureVideoId,
            ]);
        });
    }

    public function destroy($featuredVideoId)
    {
        return FeaturedVideoRelatedUnit::where('featured_video_id', $featuredVideoId)->delete();
    }
}
