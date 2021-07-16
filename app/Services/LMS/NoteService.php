<?php

namespace App\Services\LMS;

use App\Models\Note;
use App\Models\Role;
use App\Models\SpecificDealer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NoteService
{
    private $users;

    public function __construct()
    {
        if (auth()->user() && auth()->user()->specific_dealer_id) {
            $specific_dealer = SpecificDealer::find(auth()->user()->specific_dealer_id);
            $this->users = $specific_dealer->users->pluck('id')->toArray();
        } else {
            $this->users = (auth()->user()) ? auth()->user()->dealer->users->pluck('id')->toArray() : [];
        }
    }

    public function index($unitId)
    {
        return $this->getNotes($this->users, $unitId);
    }

    public function store($request)
    {
        $note = Note::create([
            'user_id' => auth()->user()->id,
            'unit_id' => $request['unitId'],
            'value' => $request['value'],
        ]);

        return [
            'note' => Note::find($note->id),
            'count' => Note::whereIn('user_id', $this->users)
                ->where('unit_id', $request['unitId'])
                ->count(),
        ];
    }

    public function update($request)
    {
        $note = Note::find($request['id']);
        $note->value = $request['value'];
        $note->save();

        return $note;
    }

    public function delete($request)
    {
        return Note::destroy($request['id']);
    }

    public function getNotes($users, $unitId)
    {
        return [
            'notes' => Note::fetchNotes($this->users, $unitId),
            'count' => Note::whereIn('user_id', $this->users),
        ];
    }
}
