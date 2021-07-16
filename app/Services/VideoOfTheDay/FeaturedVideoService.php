<?php

namespace App\Services\VideoOfTheDay;

use App\Models\FeaturedVideo;
use App\Services\Notification\GetUsersList;
use Carbon\Carbon;

class FeaturedVideoService
{
    private $getUsersList;

    private $featuredVideoRelatedUnitService;

    public function __construct(GetUsersList $getUsersList, FeaturedVideoRelatedUnitService $featuredVideoRelatedUnitService)
    {
        $this->getUsersList = $getUsersList;
        $this->featuredVideoRelatedUnitService = $featuredVideoRelatedUnitService;
    }

    public function show($request)
    {
        return FeaturedVideo::with(['relatedUnits'])
            ->orderBy('start_at', 'desc')
            ->paginate($request->perPage ?: 5);
    }

    public function store($request)
    {
        $featuredVideo = FeaturedVideo::create([
            'title' => $request->name,
            'url' => $request->videoUrl,
            'start_at' => $request->startDate,
            'end_at'   => $request->endDate,
        ]);

        $this->featuredVideoRelatedUnitService->store($featuredVideo->id, $request->relatedUnits);

        return $featuredVideo;
    }

    public function searchById($id)
    {
        return response()->json(FeaturedVideo::findOrFail($id));
    }

    public function update($request)
    {
        $table = FeaturedVideo::find($request->id);
        $table->title = $request->name;
        $table->url = $request->videoUrl;
        $table->start_at = $request->startDate;
        $table->end_at = $request->endDate;
        $table->save();

        $this->featuredVideoRelatedUnitService->update($request->id, $request->relatedUnits);

        return FeaturedVideo::with(['relatedUnits'])->find($request->id);
    }

    public function destroy($request)
    {
        $this->featuredVideoRelatedUnitService->destroy($request);

        return FeaturedVideo::destroy($request);
    }
}
