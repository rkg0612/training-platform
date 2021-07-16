<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class IntroVideoController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAsWatched(Request $request)
    {
        $moduleId = $request->module_id;
        $module = Module::findOrFail($moduleId);

        if (! $this->user->modules->contains($module->id)) {
            $this->user->modules()->save($module, ['intro_video_watched' => true]);
        } else {
            $this->user->modules()->updateExistingPivot($module, ['intro_video_watched' => true]);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }
}
