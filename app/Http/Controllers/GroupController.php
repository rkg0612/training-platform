<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        $groups = [];

        if ($currentUser->isAdmin()) {
            $groups = Group::with(['users'])->get();
        } elseif ($currentUser->isAccountManager()) {
            $groups = Group::with(['users'])->where('dealer_id', $currentUser->dealer_id)->get();
        } elseif ($currentUser->isSpecificDealerManager() && $currentUser->specific_dealer_id) {
            $groups = Group::with(['users'])->where('specific_dealer_id', $currentUser->specific_dealer_id)->get();
        }

        return response($groups);
    }

    public function store(Request $request)
    {
        $group = Group::create($request->except(['users']));

        if ($request->has('users')) {
            $group->users()->attach($request->input('users'));
        }

        return response($group->load('users'));
    }

    public function update(Request $request, Group $group)
    {
        $group->update($request->except(['users']));

        $group->users()->detach();
        if ($request->has('users')) {
            $group->users()->attach($request->input('users'));
        }

        return response($group->load('users'));
    }

    public function destroy($id)
    {
        if (Group::destroy($id)) {
            return response()->json([
                'success'   =>  true,
            ]);
        }
        abort(404);
    }
}
