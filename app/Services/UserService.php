<?php

namespace App\Services;

use App\Helpers\UserRegister;
use App\Mail\UserGotApproved;
use App\Models\Role;
use App\Models\SpecificDealer;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Mail;

class UserService
{
    private $syncUser;

    public function __construct(UserRegister $syncUser)
    {
        $this->syncUser = $syncUser;
    }

    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $page = isset($request->current_page) ? $request->current_page : 1;
        $sortBy = isset($request->sortBy) ? $request->sortBy : '';
        $sortDesc = $request->has('sortDesc') && $request->sortDesc == 'true' ? 'DESC' : 'ASC';
        $search = $request->search;

        $currentUser = auth()->user();

        $users = User::withoutGlobalScopes()->with(['dealer', 'specificDealer', 'roles'])->where(function ($query) use ($currentUser) {
            if ($currentUser->roles->pluck('name')->contains(Role::SPECIFIC_DEALER_MANAGER)) {
                $query->whereNotNull('users.specific_dealer_id')->where('users.specific_dealer_id', $currentUser->specific_dealer_id);
            }

            if ($currentUser->roles->pluck('name')->contains(Role::ACCOUNT_MANAGER)) {
                $query->whereNotNull('users.dealer_id')->where('users.dealer_id', $currentUser->dealer_id);
            }
        });

        if (! empty($sortBy)) {
            if ($sortBy == 'dealer') {
                $users->select('users.*')->addSelect('dealers.name AS dealername')->join('dealers', 'dealers.id', '=', 'users.dealer_id')->orderBy('dealername', $sortDesc);
            } elseif ($sortBy == 'specificDealer') {
                $users->select('users.*')->addSelect('specific_dealers.name AS specificDealerName')->join('specific_dealers', 'specific_dealers.id', '=', 'users.specific_dealer_id')->orderBy('specificDealerName', $sortDesc);
            } elseif ($sortBy == 'roles') {
                $users->select('users.*')->addSelect('role_user.user_id AS roleuserid')->addSelect('roles.name AS rolename')->join('role_user', 'role_user.user_id', '=', 'users.id')->join('roles', 'role_user.role_id', '=', 'roles.id')->orderBy('rolename', $sortDesc);
            } else {
                $users->orderBy($sortBy, $sortDesc);
            }
        }

        if (! empty($search)) {
            $users->where('users.name', 'like', '%'.$search.'%');
        }

        return response([
            'total' => $users->count(),
            'data' => $users->forPage($page, $perPage)->get(),
            'current_page' => $page,
            'per_page' => $perPage,
        ]);
    }

    public function store($request)
    {
        $specificDealerFirstRecord = -1;
        $currentUser = auth()->user();

        $data = $request->all();
        $data['password'] = bcrypt('Webinarinc123');
        $data['status'] = User::ACTIVE;
        if ($currentUser->roles->pluck('name')->contains(Role::SPECIFIC_DEALER_MANAGER)) {
            $data['specific_dealer_id'] = $currentUser->specific_dealer_id;
            $data['dealer_id'] = $currentUser->dealer_id;
        }

        if (is_array($data['specific_dealer_id'])) {
            $specificDealerFirstRecord = SpecificDealer::where('dealer_id', $data['dealer_id'])
                ->where('name', 'like', '%'.$data['specific_dealer_id']['name'].'%')
                ->first();
            // create new specific dealer
            if ($specificDealerFirstRecord == null) {
                $newSpecificDealer = SpecificDealer::create([
                    'dealer_id' => $data['dealer_id'],
                    'name'      => $data['specific_dealer_id']['name'],
                ]);
                $data['specific_dealer_id'] = $newSpecificDealer->id;
            } else {
                $data['specific_dealer_id'] = $specificDealerFirstRecord->id;
            }
        }

        $user = User::create($data);
        $roles = collect($request->roles);
        $roles->each(function ($role) use ($user) {
            $user->roles()->attach($role);
        });

        // $this->syncUser->createRecord($user);

        Mail::to($user->email)->queue(new UserGotApproved($user));

        return response()->json([
            'user' => $user->load(['dealer', 'roles']),
            'specificDealer' => ($specificDealerFirstRecord == null) ? $newSpecificDealer : null,
        ]);
    }

    public function update($request, $user)
    {
        $specificDealerFirstRecord = -1;
        $data = $request->all();
        if (is_array($data['specific_dealer_id'])) {
            $specificDealerFirstRecord = SpecificDealer::where('dealer_id', $data['dealer_id'])
                ->where('name', 'like', '%'.$data['specific_dealer_id']['name'].'%')
                ->first();
            // create new specific dealer
            if ($specificDealerFirstRecord == null) {
                $newSpecificDealer = SpecificDealer::create([
                    'dealer_id' => $data['dealer_id'],
                    'name'      => $data['specific_dealer_id']['name'],
                ]);
                $data['specific_dealer_id'] = $newSpecificDealer->id;
            } else {
                $data['specific_dealer_id'] = $specificDealerFirstRecord->id;
            }
        }

        if ($request->password) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);

        $user->roles()->detach();
        $roles = collect($data['roles']);
        $roles->each(function ($role) use ($user) {
            $user->roles()->attach($role);
        });

        return response()->json([
            'user' => $user->load(['dealer', 'roles']),
            'specificDealer' => ($specificDealerFirstRecord == null) ? $newSpecificDealer : null,
        ]);
    }

    public function destroy($user)
    {
        $user->roles()->detach();
        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted.',
        ]);
    }
}
