<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Module;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileTransferFromImgurToS3Service
{
    public function __construct(array $args)
    {
        if (in_array('units', $args)) {
            return $this->transferUnitsImages();
        }

        if (in_array('categories', $args)) {
            return $this->transferCategoriesImages();
        }

        if (in_array('modules', $args)) {
            return $this->transferModulesImages();
        }
    }

    public static function transfer(array $args)
    {
        return new self($args);
    }

    public function transferUnitsImages()
    {
        $units = Unit::where('thumbnail', 'LIKE', '%imgur.com%')
            ->orWhere('thumbnail', 'LIKE', '%drive.google.com%')
            ->get();

        $units->each(function ($unit) {
            $image = file_get_contents($unit->thumbnail);

            if ($image === null) {
                return false;
            }

            $path = 'units/'.$unit->id.'/'.Str::random(20).'.jpg';

            Storage::disk('s3')->put($path, $image, 'public');

            return Unit::find($unit->id)->update([
                'thumbnail' => Storage::disk('s3')->url($path),
            ]);
        });
    }

    public function transferCategoriesImages()
    {
        $categories = Category::where('thumbnail', 'LIKE', '%imgur.com%')
            ->orWhere('thumbnail', 'LIKE', '%drive.google.com%')
            ->get();

        $categories->each(function ($category) {
            $image = file_get_contents($category->thumbnail);

            if ($image === null) {
                return false;
            }

            $path = 'categories/'.$category->id.'/'.Str::random(20).'.jpg';

            Storage::disk('s3')->put($path, $image->stream('jpg'), 'public');

            return Category::find($category->id)->update([
                'thumbnail' => Storage::disk('s3')->url($path),
            ]);
        });
    }

    public function transferModulesImages()
    {
        $modules = Module::where('thumbnail', 'LIKE', '%imgur.com%')
            ->orWhere('thumbnail', 'LIKE', '%drive.google.com%')
            ->get();

        $modules->each(function ($module) {
            $image = file_get_contents($module->thumbnail);

            if ($image === null) {
                return false;
            }

            $path = 'modules/'.$module->id.'/'.Str::random(20).'.jpg';

            Storage::disk('s3')->put($path, $image, 'public');

            return Module::find($module->id)->update([
                'thumbnail' => Storage::disk('s3')->url($path),
            ]);
        });
    }
}
