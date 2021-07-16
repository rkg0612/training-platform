<?php

namespace App\Services\LMS;

use App\Models\CategoryExcludeUser;

class CategoryExcludeUserService
{
    public function store($users, $categoryId)
    {
        collect($users)->each(function ($user) use ($categoryId) {
            CategoryExcludeUser::create([
                'user_id' => $user,
                'category_id' => $categoryId,
            ]);
        });
    }

    public function update($users, $categoryId)
    {
        $records = CategoryExcludeUser::where('category_id', $categoryId)->pluck('user_id');
        $records->each(function ($record) use ($users, $categoryId) {
            if (! in_array($record, $users->toArray())) {
                CategoryExcludeUser::where('category_id', $categoryId)
                    ->where('user_id', $record)
                    ->delete();
            }
        });
        $users->each(function ($user) use ($records, $categoryId) {
            if (! in_array($user, $records->toArray())) {
                CategoryExcludeUser::create([
                    'category_id' => $categoryId,
                    'user_id' => $user,
                ]);
            }
        });
    }
}
