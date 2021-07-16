<?php

namespace App\Services\LMS;

use App\Helpers\LMSRestrictUser;
use App\Models\AssignedPlaylist;
use App\Models\Note;
use App\Models\Role;
use App\Models\Unit;
use App\Models\UserUnit;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use voku\helper\HtmlDomParser;

class UnitClientService
{
    public function show($id)
    {
        if (LMSRestrictUser::make(auth()->user()->id, $id, 'unit')->isContentNotAccessible()) {
            abort(404);
        }

        $unit = Unit::with(['tags', 'module', 'module.build', 'assignedBy', 'quiz', 'quiz.questions.answers'])
            ->where('id', $id)
            ->first();

        return response()->json([
            'unit' => $unit,
            'is_completed' => $this->isCompleted($id),
            'is_played' => $this->isPlayed($id),
        ]);
    }

    public function isCompleted($unitId)
    {
        $assignedCount = AssignedPlaylist::whereHas('assignedPlaylistUnit', function ($query) use ($unitId) {
            $query->where('unit_id', $unitId)
                ->whereNotNull('date_completed');
        })
            ->where('assignee_id', auth()->user()->id)
            ->count();
        $userUnit = UserUnit::where('unit_id', $unitId)
            ->where('user_id', auth()->user()->id)
            ->whereNotNull('date_completed')
            ->count();

        if ($assignedCount || $userUnit) {
            return 1;
        }

        return 0;
    }

    private function isPlayed($unitId)
    {
        $assignedCount = AssignedPlaylist::whereHas('assignedPlaylistUnit', function ($query) use ($unitId) {
            $query->where('unit_id', $unitId)
                ->where('played', true);
        })
            ->where('assignee_id', auth()->user()->id)
            ->count();
        $userUnit = UserUnit::where('unit_id', $unitId)
            ->where('user_id', auth()->user()->id)
            ->where('played', true)
            ->count();

        if ($assignedCount || $userUnit) {
            return 1;
        }

        return 0;
    }
}
