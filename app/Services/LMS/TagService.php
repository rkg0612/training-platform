<?php

namespace App\Services\LMS;

use App\Models\Tag;

class TagService
{
    public static function index($request)
    {
        return Tag::all();
    }
}
