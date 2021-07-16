<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function __invoke(Request $request)
    {
        $path = Storage::get($request->path.'/'.$request->fileName);
        if ($request->height && $request->width) {
            return Image::make($path)->resize($request->height, $request->width, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->response();
        }

        return Image::make($path)->response();
    }
}
