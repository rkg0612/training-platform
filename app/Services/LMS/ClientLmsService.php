<?php

namespace App\Services\LMS;

use App\Models\AssignedModule;
use App\Models\AssignedPlaylist;
use App\Models\Course;
use App\Models\CourseBuild;
use App\Models\ModuleBuild;
use App\Models\Playlist;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClientLmsService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function fetchCourseLibrary($userArg = null)
    {
        $user = is_null($userArg) ? $this->user : $userArg;
        $build = CourseBuild::whereIn('course_id', $user->dealer->courses->pluck('id'))
            ->first();

        if (empty($build) && is_null($userArg)) {
            abort(404);
        }

        if (empty($build) && ! is_null($userArg)) {
            return;
        }

        $menuCacheName = "dealer_{$user->dealer->id}_sidebar_menu";

        if (Cache::has($menuCacheName)) {
            return Cache::get($menuCacheName);
        }

        $result = [];
        foreach ($build->category_builds as $categoryBuild) {
            $category = [];
            $category['name'] = $categoryBuild->category_build->category->name;
            $category['modules'] = $categoryBuild->category_build->modules->map(function ($item) {
                $module = [];
                $module['name'] = $item->module_build->module->name;
                $module['module_id'] = $item->module_build->module_id;
                $module['units'] = $item->module_build->series->map(function ($item) {
                    $units = $item->units->pluck('unitForMenu');
                    $result = collect();
                    foreach ($units as $unit) {
                        $result->push([
                            'id' => $unit->id,
                            'name' => $unit->name,
                        ]);
                    }

                    return $result;
                })->flatten(1);

                return $module;
            });

            $result[] = $category;
        }

        Cache::put($menuCacheName, $result, now('America/New_York')->endOfDay());

        return $result;
    }

    public function fetchCourseLibraryLazied($request)
    {
        $currentBuild = $request->has('current_build') ? $request->input('current_build') : 1;
        $currentIndex = $request->has('current_index') ? $request->input('current_index') : 1;
        $build = CourseBuild::whereIn('course_id', $this->user->dealer->courses->pluck('id'))
            ->skip($currentBuild - 1)->take(1)->first();

        if (empty($build)) {
            $currentBuild += 1;
            $currentIndex = 1;

            $build = CourseBuild::whereIn('course_id', $this->user->dealer->courses->pluck('id'))
                                ->skip($currentBuild - 1)->take(1)->first();

            if (empty($build)) {
                return response('0');
            }

            $categoryBuild = $build->category_builds->skip($currentIndex - 1)->take(1)->first();
            if ($categoryBuild == null) {
                return response('0');
            }
        }

        // we start 0,0
        // iterate $categoryBuild first if end iterate $build if end throw 404
        // return current value of page and index
        $result = [];
        $categoryBuild = $build->category_builds->skip($currentIndex - 1)->take(1)->first();

        // manual iterate is the thing
        if ($categoryBuild == null) {
            $currentBuild += 1;
            $currentIndex = 1;

            $build = CourseBuild::whereIn('course_id', $this->user->dealer->courses->pluck('id'))
                                ->skip($currentBuild - 1)->take(1)->first();

            if (empty($build)) {
                return response('0');
            }

            $categoryBuild = $build->category_builds->skip($currentIndex - 1)->take(1)->first();
            if ($categoryBuild == null) {
                return response('0');
            }
        }

        $category = [];
        $category['name'] = $categoryBuild->category_build->name;
        $category['modules'] = $categoryBuild->category_build->modules->map(function ($item) {
            $module = [];
            $module['name'] = $item->module_build->name;
            $module['progress'] = $item->module_build->progress;
            $module['module'] = [
                'id'  =>      $item->module_build->module->id,
                'name'  =>      $item->module_build->module->name,
                'description'  =>      $item->module_build->module->description,
                'thumbnail'  =>      $item->module_build->module->thumbnail,
                'my_assigned'  =>      $item->module_build->module->myAssigned,
                'intro_video_watched'  =>      $item->module_build->module->intro_video_watched,
                'intro_video_played'  =>      $item->module_build->module->intro_video_played,
            ];
            $module['series'] = $item->module_build->series->map(function ($series_item) {
                $series = [];
                $series['name'] = $series_item->name;
                $series['is_banner'] = $series_item->is_banner;
                $series['units'] = $series_item->units->map(function ($unit_item) {
                    $unit = [];
                    $unit['id'] = $unit_item->unit->id;
                    $unit['name'] = $unit_item->unit->name;
                    $unit['description'] = $unit_item->unit->description;
                    $unit['thumbnail'] = $unit_item->unit->thumbnail;
                    $unit['video_duration'] = $unit_item->unit->video_duration;
                    $unit['is_completed'] = $unit_item->unit->is_completed;
                    $unit['due_date'] = $unit_item->unit->due_date;
                    $unit['shared_by'] = $unit_item->unit->shared_by;
                    $unit['tags'] = $unit_item->unit->tags->map(function ($tags_item) {
                        $tags = [];
                        $tags['name'] = $tags_item->name;

                        return $tags;
                    });

                    return $unit;
                });

                return $series;
            });

            return $module;
        });

        $result = $category;

        return response()->json([
            'data' => $result,
            'next_index' => $currentIndex + 1,
            'next_build' => $currentBuild,
        ]);
    }

    public function fetchCourseLibraryHome()
    {
        $builds = CourseBuild::whereIn('course_id', $this->user->dealer->courses->pluck('id'))
            ->get();

        if (empty($builds)) {
            abort(404);
        }

        $result = [];
        foreach ($builds as $i => $build) {
            foreach ($build->category_builds as $categoryBuild) {
                $category = [];
                $category['name'] = $categoryBuild->category_build->name;
                $category['modules'] = $categoryBuild->category_build->modules->map(function ($item) {
                    $module = [];
                    $module['name'] = $item->module_build->name;
                    $module['progress'] = $item->module_build->progress;
                    $module['module'] = [
                        'id'  =>      $item->module_build->module->id,
                        'name'  =>      $item->module_build->module->name,
                        'description'  =>      $item->module_build->module->description,
                        'thumbnail'  =>      $item->module_build->module->thumbnail,
                        'my_assigned'  =>      $item->module_build->module->myAssigned,
                    ];
                    $module['series'] = $item->module_build->series->map(function ($series_item) {
                        $series = [];
                        $series['name'] = $series_item->name;
                        $series['is_banner'] = $series_item->is_banner;
                        $series['units'] = $series_item->units->map(function ($unit_item) {
                            $unit = [];
                            $unit['id'] = $unit_item->unit->id;
                            $unit['name'] = $unit_item->unit->name;
                            $unit['thumbnail'] = $unit_item->unit->thumbnail;
                            $unit['video_duration'] = $unit_item->unit->video_duration;
                            $unit['is_completed'] = $unit_item->unit->is_completed;
                            $unit['due_date'] = $unit_item->unit->due_date;
                            $unit['shared_by'] = $unit_item->unit->shared_by;
                            $unit['tags'] = $unit_item->unit->tags->map(function ($tags_item) {
                                $tags = [];
                                $tags['name'] = $tags_item->name;

                                return $tags;
                            });

                            return $unit;
                        });

                        return $series;
                    });

                    return $module;
                });

                $result[] = $category;
            }
        }

        return $result;
    }

    public function fetchAssignedUnits()
    {
        $assigned = $this->user->units()->whereNotNull('due_date')->get();
        $shared = $this->user->units()->whereNotNull('shared_by')->get();
        $units = $assigned->merge($shared);

        return $units;
    }

    public function fetchPlaylists()
    {
        $user_id = $this->user->id;
        $user_playlists = Playlist::with('assignedPlaylist', 'assignedPlaylist.user')
            ->where('user_id', $user_id)
            ->orWhereHas('assignedPlaylist', function ($query) use ($user_id) {
                $query->where('assignee_id', $user_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $result = [];

        foreach ($user_playlists as $user_playlist) {
            $playlist = [];
            $playlist['id'] = $user_playlist->id;
            $playlist['name'] = $user_playlist->name;

            $assigned_by = $user_playlist->assignedPlaylist()->where('assignee_id', $this->user->id)->first();

            $playlist['assigned_by'] = $assigned_by ? $assigned_by->user->name : null;
            $result[] = $playlist;
        }

        return $result;
    }

    public function fetchInProgressModule()
    {
        $latest_unit = UserUnit::where('user_id', $this->user->id)
            ->orderByDesc('date_completed')
            ->first();

        if (! $latest_unit) {
            return false;
        }

        if ($latest_unit->unit->module->build->progress >= 100) {
            $latest_unit = UserUnit::where('user_id', $this->user->id)
                ->whereNotIn('id', [$latest_unit->id])
                ->orderByDesc('date_completed')
                ->first();
        }

        $result = [
            'id'            =>  $latest_unit->unit->module->id,
            'name'          =>  $latest_unit->unit->module->build->name,
            'thumbnail'     =>  $latest_unit->unit->module->thumbnail,
            'progress'      =>  $latest_unit->unit->module->build->progress,
        ];

        return $result;
    }

    public function fetchLatestAssignedModule()
    {
        $assigned_modules = AssignedModule::with('module', 'user')
            ->where('assignee_id', $this->user->id)
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'desc')
            ->get();

        $assigned_playlists = AssignedPlaylist::with('user')
            ->where('assignee_id', $this->user->id)
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'desc')
            ->get();

        $result = collect();

        foreach ($assigned_modules as $assigned_module) {
            $ret_assigned_module = [];
            $ret_assigned_module['id'] = $assigned_module->id;
            $ret_assigned_module['name'] = $assigned_module->module->name;
            $ret_assigned_module['assigned_by'] = $assigned_module->user->name;
            $ret_assigned_module['due_date'] = $assigned_module->due_date->format('Y-m-d');
            $ret_assigned_module['url'] = '/client/lms/module/'.$assigned_module->module->id;
            $result->push($ret_assigned_module);
        }

        foreach ($assigned_playlists as $assigned_playlist) {
            $ret_assigned_playlist = [];
            $ret_assigned_playlist['id'] = $assigned_playlist->id;
            $ret_assigned_playlist['name'] = $assigned_playlist->playlist->name;
            $ret_assigned_playlist['assigned_by'] = $assigned_playlist->user->name;
            $ret_assigned_playlist['due_date'] = $assigned_playlist->due_date->format('Y-m-d');
            $ret_assigned_playlist['url'] = '/playlist'.'/'.$assigned_playlist->playlist_id;
            $result->push($ret_assigned_playlist);
        }

        return response()->json($result->sortByDesc('due_date')->take(10));
    }
}
