<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitNotePostingRequest;
use App\Http\Requests\UnitNoteUpdateRequest;
use App\Services\LMS\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function index($unitId)
    {
        return response()->json($this->noteService->index($unitId));
    }

    public function store(UnitNotePostingRequest $request)
    {
        return response()->json($this->noteService->store($request));
    }

    public function show($id)
    {
        //
    }

    public function update(UnitNoteUpdateRequest $request)
    {
        return response()->json($this->noteService->update($request));
    }

    public function destroy($id)
    {
        //
    }
}
