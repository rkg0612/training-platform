<?php

namespace App\Services\LMS\RelatedUnit;

use App\Models\AssignedPlaylist;
use App\Models\ModuleBuild;
use App\Models\UserUnit;

class SingleUnitRelatedUnitsService
{
    public function show($request)
    {
        $moduleBuild = ModuleBuild::whereHas('module.units', function ($query) use ($request) {
            $query->where('id', $request['unitId']);
        })->get();

        return $moduleBuild->map(function ($moduleBuild) {
            return (object) [
                'id' => $moduleBuild->id,
                'name' => $moduleBuild->name,
                'series' => $moduleBuild
                    ->series()
                    ->get()
                    ->map(function ($series) {
                        return (object) [
                            'id' => $series->id,
                            'name' => $series->name,
                            'units' => $series->units->map(function ($series) {
                                return (object) [
                                    'id' => $series->unit->id,
                                    'name' => $series->unit->name,
                                    'is_completed' => $this->isCompleted($series->unit->id),
                                ];
                            }),
                        ];
                    }),
            ];
        });
    }

    public function isCompleted($unitId)
    {
        return UserUnit::where('unit_id', $unitId)
            ->where('user_id', auth()->user()->id)
            ->whereNotNull('date_completed')
            ->count();
    }

    public function getPage($request)
    {
        $unitId = $request['unitId'];

        $build = ModuleBuild::whereHas('module.units', function ($query) use ($unitId) {
            $query->where('id', $unitId);
        })->first();

        $series = $build->series()->get();

        $unitIds = $series
            ->map(function ($series) {
                return $series->units->pluck('unit_id');
            })
            ->flatten()
            ->toArray();

        if ($request['action'] == 'next') {
            return $this->nextUnit($unitIds, $unitId);
        }

        return $this->prevUnit($unitIds, $unitId);
    }

    public function nextUnit($unitList, $currentUnitId)
    {
        $index = array_search($currentUnitId, $unitList);
        $index = $index + 1;

        if ($index > array_key_last($unitList)) {
            $index = 0;
        }

        return $unitList[$index];
    }

    public function prevUnit($unitList, $currentUnitId)
    {
        $index = array_search($currentUnitId, $unitList);
        $index = $index - 1;

        if ($index < 0) {
            $index = array_key_last($unitList);
        }

        return $unitList[$index];
    }
}
