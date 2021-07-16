<?php

namespace App\Services;

use App\Models\Category;

class CategoryPageSliderService
{
    public function index()
    {
        return Category::whereHas('course.dealers', function ($query) {
            $query->where('dealer_id', auth()->user()->dealer_id);
        })->get();
    }

    public function show($id)
    {
        return Category::with('modules.build.series', 'modules.build.module')
            ->where('id', $id)
            ->first();
    }
}
