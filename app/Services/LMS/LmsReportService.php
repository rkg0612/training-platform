<?php

namespace App\Services\LMS;

use App\Exports\ReportExport\ModuleProgressExport;
use App\Exports\ReportExport\OverviewExport;
use App\Exports\ReportExport\TeamProgressExport;
use App\Models\AssignedModule;
use App\Models\AssignedPlaylist;
use App\Models\AssignedPlaylistUnit;
use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleBuild;
use App\Models\Note;
use App\Models\SpecificDealer;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserAnsweredQuiz;
use App\Models\UserUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LmsReportService
{
    private $user;
    private $filepath;
    private $moduleUnitsId = [];
    private Collection $unitNotes;
    private Collection $quizScores;
    private Collection $assignedPlaylistUnit;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->filepath = 'export/reports/';
    }

    public function exportData($request)
    {
        $export = null;
        $data = $request->data;
        $filename = Carbon::now()->format('Ymdhms').'-'.$this->user->id;

        if ($request->has('type')) {
            switch ($request->type) {
            case 'module-progress':
                $export = new ModuleProgressExport($data);
                $filename = $filename.'-module-progress.xlsx';
                break;
            case 'overview':
                $export = new OverviewExport($data);
                $filename = $filename.'-overview.xlsx';
                break;
            case 'team-progress':
                $export = new TeamProgressExport($data);
                $filename = $filename.'-team-progress.xlsx';
                break;
            default:
                abort(404);
            }
            if (($export)->store($this->filepath.$filename, 's3', null, ['visibility' =>  'public'])) {
                return response()->json([
                    'success'   =>  true,
                    'filepath'  =>  Storage::disk('s3')->url($this->filepath.$filename),
                ]);
            }
        } else {
            abort(404);
        }
    }

    public function index($request)
    {
        $users = User::whereIn('id', $request->users)->get();
        $module_ids = $request->module_ids;
        $outstanding_module_id = $request->outstanding_module_id;
        $outstanding_playlist_id = $request->outstanding_playlist_id;

        $course_ids = $this->user->dealer->courses->pluck('id');

        $result = [];

        $modules = Module::with([
            'units.users',
            'assigned',
        ])
            ->whereHas('category', function ($query) use ($course_ids) {
                $query->whereIn('course_id', $course_ids);
            })
            ->get();

        $this->setRequiredEntities($request, $modules);

        foreach ($users as $user) {
            $units = collect();

            if (
                ! empty($module_ids)
                && ! in_array(0, $module_ids)
                && ! $outstanding_module_id
            ) {
                $modules = $modules->filter(function ($module) use ($module_ids) {
                    return in_array($module->id, $module_ids);
                });
            }

            if ($outstanding_playlist_id || $outstanding_module_id) {
                if ($outstanding_playlist_id) {
                    $playlists = AssignedPlaylist::where('assignee_id', $user->id)
                        ->where('playlist_id', $outstanding_playlist_id)
                        ->get();
                    if ($playlists) {
                        foreach ($playlists as $i => $playlist) {
                            foreach ($playlist->playlist->units as $unit) {
                                $units[] = $this->generateUnit($unit, $user, $playlist->playlist->name, 'Assigned Playlist', $playlist->id);
                            }
                        }
                    }
                }
                if ($outstanding_module_id) {
                    $modules->whereHas('assigned', function ($query) use ($user, $outstanding_module_id) {
                        $query->where('assignee_id', $user->id)->where('module_id', $outstanding_module_id);
                    });

                    foreach ($modules as $i => $module) {
                        foreach ($module->units as $unit) {
                            $units[] = $this->generateUnit($unit, $user, $module->name, 'Assigned Module');
                        }
                    }
                }
            } else {
                foreach ($modules as $i => $module) {
                    foreach ($module->units as $unit) {
                        $units[] = $this->generateUnit($unit, $user, $module->name, 'Module');
                    }
                }
            }

            $units = array_values($units->sortBy('date_completed')->toArray());

            $result[] = [
                'id'    =>  $user->id,
                'name'  =>  $user->name,
                'overall_progress'  =>  $this->calculateProgress($user, $module_ids, $outstanding_module_id, $outstanding_playlist_id),
                'units' =>  $units,
            ];
        }

        // Flags if user is requesting for an export
        if ($request->has('download')) {
            $filename = Carbon::now()->format('Ymdhms').'-'.$this->user->id.'-module-progress.xlsx';
            if ((new ModuleProgressExport($result))->store($this->filepath.$filename, 's3', null, ['visibility' =>  'public'])) {
                return response()->json([
                    'success'   =>  true,
                    'filepath'  =>  Storage::disk('s3')->url($this->filepath.$filename),
                ]);
            }
        }

        return $result;
    }

    private function generateUnit($unit, $user, $name, $type, $id = null)
    {
        $res = [];
        $res['name'] = $unit->name;
        $res['module'] = $name;
        $res['type'] = $type;
        $status = 'Open';
        $date_completed = 'N/A';
        $played = false;

        if ($type === 'Assigned Playlist') {
            $checkUnit = $this->getAssignedPlaylist($id, $unit->id);

            if ($checkUnit) {
                $status = $checkUnit->date_completed ? 'Completed' : 'Assigned';
                $date_completed = $checkUnit->date_completed ?: 'N/A';
                $played = $checkUnit->played;
            } else {
                $status = 'Assigned';
                $date_completed = 'N/A';
                $played = false;
            }
        } else {
            $checkUnit = $unit->users->where('id', $user->id)->first();

            if ($checkUnit) {
                if ($checkUnit->pivot->due_date !== null) {
                    $status = 'Assigned';
                }

                if ($checkUnit->pivot->date_completed !== null) {
                    $status = 'Completed';
                    $date_completed = $checkUnit->pivot->date_completed;
                }

                if ($checkUnit->pivot->played === 1) {
                    $played = true;
                }
            }
        }

        $res['status'] = $status;
        $res['date_completed'] = $date_completed;
        $res['played'] = $played ? 'YES' : 'NO';

        $notes = $this->getNotes($user->id, $unit->id);

        $res['quizScore'] = null;
        $res['quizForm'] = null;

        $quizScore = $this->getQuizScores($user->id, $unit->id);

        if ($quizScore) {
            $res['quizScore'] = $quizScore->total_score;
            $res['quizForm'] = $quizScore->quiz_form;
        }

        $res['hasNote'] = ! empty($notes) ? 'YES' : 'NO';
        $res['notes'] = $notes;

        return $res;
    }

    private function calculateProgress($user, $module_ids = [], $outstanding_module_id = null, $outstanding_playlist_id = null)
    {
        $course_ids = $user->dealer->courses->pluck('id');
        $playlists_units_total = 0;
        $user_playlist_units_total = 0;
        $modules_units_total = 0;
        $user_modules_units_total = 0;

        $modules = Module::select('name')
            ->withCount('units')
            ->without('assigned', 'category')
            ->whereHas('category', function ($query) use ($course_ids) {
                $query->whereIn('course_id', $course_ids);
            });

        $user_units = UserUnit::where('user_id', $user->id)
            ->whereNotNull('date_completed');

        if (! empty($module_ids) && ! in_array(0, $module_ids) && ! $outstanding_module_id) {
            $modules->whereIn('id', $module_ids);
            $user_units->whereHas('unit', function ($query) use ($module_ids) {
                $query->whereIn('module_id', $module_ids);
            });
        }

        if ($outstanding_playlist_id || $outstanding_module_id) {
            if ($outstanding_playlist_id) {
                $playlist = AssignedPlaylist::where('playlist_id', $outstanding_playlist_id)
                    ->where('assignee_id', $user->id)
                    ->first();
                if ($playlist) {
                    $playlists_units_total = $playlist->playlist->units->count();
                    $user_playlist_units_total = $playlist->assignedPlaylistUnit->count();
                }
            }
            if ($outstanding_module_id) {
                $modules->whereHas('assigned', function ($query) use ($user, $outstanding_module_id) {
                    $query->where('assignee_id', $user->id)->where('module_id', $outstanding_module_id);
                });
                $user_units->whereHas('unit.module', function ($query) use ($outstanding_module_id) {
                    $query->where('id', $outstanding_module_id);
                });

                $modules = $modules->get()->toArray();
                $modules_units_total = array_sum(array_column($modules, 'units_count'));
                $user_modules_units_total = $user_units->get()->unique('unit_id')->count();
            }
        } else {
            $modules = $modules->get()->toArray();
            $modules_units_total = array_sum(array_column($modules, 'units_count'));
            $user_modules_units_total = $user_units->get()->unique('unit_id')->count();
        }

        $units_total = $modules_units_total + $playlists_units_total;
        $user_units_total = $user_modules_units_total + $user_playlist_units_total;

        $total = 0;

        if ($user_units_total !== 0 && $units_total !== 0) {
            $total = ($user_units_total / $units_total) * 100;
        }

        $result = number_format($total, 2, '.', '');

        return $result;
    }

    public function getUsers($request)
    {
        $outstanding_module_id = $request->outstanding_module_id;
        $outstanding_playlist_id = $request->outstanding_playlist_id;
        $specific_dealers = $request->specific_dealers;
        $om_ids = [];
        $op_ids = [];

        $users = User::query();

        if (! is_null($this->user->specific_dealer_id)) {
            $users->where('specific_dealer_id', $this->user->specific_dealer_id);
        } else {
            $users->where('dealer_id', $this->user->dealer_id);
        }

        if ($specific_dealers) {
            $users->whereIn('specific_dealer_id', $specific_dealers);
        }

        if ($outstanding_module_id) {
            $om_ids = AssignedModule::where('module_id', $outstanding_module_id)
                ->whereHas('module.category', function ($query) {
                    $query->whereIn('course_id', $this->user->dealer->courses->pluck('id'));
                })
                ->get()
                ->pluck('assignee_id')
                ->toArray();
        }

        if ($outstanding_playlist_id) {
            $op_ids = AssignedPlaylist::where('playlist_id', $outstanding_playlist_id)
                ->whereHas('assignee', function ($query) {
                    $query->where('dealer_id', $this->user->dealer_id)->orWhere('specific_dealer_id', $this->user->specific_dealer_id);
                })
                ->get()
                ->pluck('assignee_id')
                ->toArray();
        }

        $ids = array_unique(array_merge($op_ids, $om_ids));

        if ($ids) {
            $users->whereIn('id', $ids);
        }

        return $users->get();
    }

    public function getSpecificDealers()
    {
        $specific_dealers = SpecificDealer::where('dealer_id', $this->user->dealer_id)
            ->orderBy('name');

        return $specific_dealers->get();
    }

    public function getModules($request)
    {
        $course_ids = $this->user->dealer->courses->pluck('id');
        $modules = Module::select('id', 'name')
            ->without('assigned', 'category')
            ->whereHas('category', function ($query) use ($course_ids) {
                $query->whereIn('course_id', $course_ids);
            })
            ->get()
            ->toArray();

        array_unshift($modules, [
            'id'    =>  0,
            'name'  =>  'Select All Modules',
        ]);

        return response()->json($modules);
    }

    public function getOutstandingModules()
    {
        $modules = AssignedModule::select('id', 'module_id')
            ->with(['module' => function ($query) {
                $query->withOut('category', 'assigned');
            }])
            ->whereHas('module.category', function ($query) {
                $query->whereIn('course_id', $this->user->dealer->courses->pluck('id'));
            })
            ->get()
            ->unique('module_id')
            ->toArray();

        return response()->json(array_values($modules));
    }

    public function getOutstandingPlaylists()
    {
        $modules = AssignedPlaylist::select('id', 'playlist_id')
            ->with('playlist')
            ->whereHas('assignee', function ($query) {
                $query->where('dealer_id', $this->user->dealer_id)->orWhere('specific_dealer_id', $this->user->specific_dealer_id);
            })
            ->get()
            ->unique('playlist_id')
            ->toArray();

        return response()->json(array_values($modules));
    }

    public function getTeamProgress(Request $request)
    {
        $user_ids = $request->user_ids;
        $module_ids = $request->module_ids;
        $outstanding_module_id = $request->outstanding_module_id;
        $outstanding_playlist_id = $request->outstanding_playlist_id;
        $show_bottom = $request->show_bottom;

        $users = User::where('dealer_id', $this->user->dealer_id);

        if ($user_ids) {
            $users->whereIn('id', $user_ids);
        }

        $users = $users->get();

        $result = [];
        foreach ($users as $user) {
            $ret = [];
            $ret['name'] = $user->name;
            $ret['progress'] = $this->calculateProgress($user, $module_ids, $outstanding_module_id, $outstanding_playlist_id);
            $result[] = $ret;
        }

        $result = collect($result);

        if ($show_bottom) {
            $result = $result
                ->reverse()
                ->splice(0, 10);
        }

        if ($request->show_top) {
            $result = $result
                ->splice(0, 10);
        }

        // Flags if user is requesting for an export
        if ($request->has('download')) {
            $filename = Carbon::now()->format('Ymdhms').'-'.$this->user->id.'-team-progress.xlsx';
            if ((new TeamProgressExport($result))->store($this->filepath.$filename, 's3', null, ['visibility' =>  'public'])) {
                return response()->json([
                    'success'   =>  true,
                    'filepath'  =>  Storage::disk('s3')->url($this->filepath.$filename),
                ]);
            }
        }

        return $result;
    }

    public function exportOverviewReport($request)
    {
        $user_ids = $request->users;
        $module_ids = $request->modules;

        $course_ids = $this->user->dealer->courses->pluck('id');
        $users = User::select('id', 'name')
            ->where('dealer_id', $this->user->dealer_id);

        $users = User::select('id', 'name')
            ->where('dealer_id', $this->user->dealer_id);

        if ($this->user->specific_dealer_id) {
            $users = $users->where('specific_dealer_id', $this->user->specific_dealer_id);
        }

        if ($user_ids) {
            $users->whereIn('id', $user_ids);
        }

        $modules = Module::whereHas('category', function ($query) use ($course_ids) {
            $query->whereIn('course_id', $course_ids);
        });

        if (! empty($module_ids) && ! in_array(0, $module_ids)) {
            $modules->whereIn('id', $module_ids);
        }

        $user_results = [];

        foreach ($users->get() as $user) {
            $user_result = [];
            $user_result['name'] = $user->name;
            foreach ($modules->get() as $module) {
                $user_result[$module->name] = $module->unitProgress($user)->count().' out '.$module->units->count();
            }
            $user_results[] = $user_result;
        }

        $filename = Carbon::now()->format('Ymdhms').'-'.$this->user->id.'-overview.xlsx';
        if ((new OverviewExport($user_results))->store($this->filepath.$filename, 's3', null, ['visibility' =>  'public'])) {
            return response()->json([
                'success'   =>  true,
                'filepath'  =>  Storage::disk('s3')->url($this->filepath.$filename),
            ]);
        }
    }

    public function getOverviewModuleHeaders($request)
    {
        $module_ids = $request->modules;
        $return = [];
        $course_ids = $this->user->dealer->courses->pluck('id');
        $modules = Module::select('id', 'name')
            ->without('assigned', 'category')
            ->whereHas('category', function ($query) use ($course_ids) {
                $query->whereIn('course_id', $course_ids);
            });

        if (! empty($module_ids) && ! in_array(0, $module_ids)) {
            $modules->whereIn('id', $module_ids);
        }

        foreach ($modules->get() as $module) {
            $module_return = [];
            $module_return['text'] = $module->name;
            $module_return['value'] = Str::slug($module->name);
            $module_return['width'] = '200px';
            $return[] = $module_return;
        }

        array_unshift($return, [
            'text'    =>  'Name of Salesperson',
            'align'    =>  'start',
            'value'  =>  'name',
            'width' =>  '200px',
        ]);

        return response()->json($return);
    }

    public function getOverviewReport($request)
    {
        $user_ids = $request->users;
        $module_ids = $request->modules;

        $course_ids = $this->user->dealer->courses->pluck('id');
        $users = User::select('id', 'name')
            ->where('dealer_id', $this->user->dealer_id);

        if ($this->user->specific_dealer_id) {
            $users = $users->where('specific_dealer_id', $this->user->specific_dealer_id);
        }

        if ($user_ids) {
            $users->whereIn('id', $user_ids);
        }

        $users = $users->paginate(10);

        $modules = Module::with(['units', 'category'])
            ->whereHas('category', function ($query) use ($course_ids) {
                $query->whereIn('course_id', $course_ids);
            });

        if (! empty($module_ids) && ! in_array(0, $module_ids)) {
            $modules->whereIn('id', $module_ids);
        }

        $modules = $modules->get();
        $usersItems = $users->items();

        $result = [];
        $userUnits = $this->getUserUnits($modules, $usersItems);

        foreach ($usersItems as $user) {
            $user_result = [];
            $user_result['name'] = $user->name;
            foreach ($modules as $module) {
                $user_result[Str::slug($module->name)] = $this->unitProgressCount($userUnits, $module, $user).' out '.$module->units->count();
            }
            $result[] = $user_result;
        }

        if (! is_null($request->sortBy)) {
            usort($result, function ($a, $b) use ($request) {
                $key = $request->sortBy;

                if ($request->sortDesc == 1) {
                    return strnatcmp($b[$key], $a[$key]);
                }

                return strnatcmp($a[$key], $b[$key]);
            });
        }

        return response()->json([
            'success'   =>  true,
            'items'     =>  $result,
            'total'     =>  $users->total(),
            'current_page'  =>  $users->currentPage(),
            'per_page'      =>  $users->perPage(),
        ]);
    }

    private function unitProgressCount(Collection $userUnits, $module, $user): int
    {
        $moduleId = $module->id;
        if (! array_key_exists($moduleId, $this->moduleUnitsId)) {
            $this->moduleUnitsId[$moduleId] = $module->units->pluck('id')->toArray();
        }

        return $userUnits->filter(function ($item) use ($user, $moduleId) {
            return $item->user_id == $user->id && in_array($item->unit_id, $this->moduleUnitsId[$moduleId]);
        })
            ->count();
    }

    private function getUserUnits(Collection $modules, $users)
    {
        $units = $modules->map(function ($module) {
            return $module->units;
        })
            ->flatten()
            ->pluck('id');
        $userIds = [];

        foreach ($users as $user) {
            $userIds[] = $user->id;
        }

        $userUnits = UserUnit::whereIn('unit_id', $units)
            ->whereIn('user_id', $userIds)
            ->whereNotNull('date_completed')
            ->get();

        return $userUnits->unique('unit_id');
    }

    private function setRequiredEntities(Request $request, Collection $modules)
    {
        $unitIds = $modules->pluck('units')
            ->map(function ($item) {
                return $item->pluck('id');
            })
            ->flatten()
            ->toArray();

        $this->unitNotes = Note::whereIn('user_id', $request->users)
            ->whereIn('unit_id', $unitIds)
            ->get();

        $this->quizScores = UserAnsweredQuiz::whereIn('user_id', $request->users)
            ->whereIn('unit_id', $unitIds)
            ->get();

        $this->assignedPlaylistUnit = AssignedPlaylistUnit::whereIn('unit_id', $unitIds)
                ->get();
    }

    private function getNotes($userId, $unitId): array
    {
        $notes = $this->unitNotes->filter(function ($note) use ($userId, $unitId) {
            return $note->user_id === $userId && $note->unit_id === $unitId;
        });

        return $notes->toArray();
    }

    private function getQuizScores($userId, $unitId)
    {
        return $this->quizScores->filter(function ($item) use ($userId, $unitId) {
            return $item->user_id === $userId && $item->unit_id === $unitId;
        })
            ->sortByDesc('created_at')
            ->first();
    }

    private function getAssignedPlaylist($id, $unit_id)
    {
        return $this->assignedPlaylistUnit->first(function ($item) use ($id, $unit_id) {
            return $item->assigned_playlist_id === $id && $item->unit_id === $unit_id;
        });
    }
}
