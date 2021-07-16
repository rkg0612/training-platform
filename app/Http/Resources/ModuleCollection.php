<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($module) {
            return (object) [
                'id' => $module->id,
                'value' => $module->value,
                'banner_link' => $module->banner_link,
                'description' => $module->description,
                'assigned_categories' => $module->categories->map(function ($module) {
                    return $module->category->value;
                })->sort()->implode(', '),
                'categories' => $module->categories->map(function ($module) {
                    return $module->category;
                }),
                'excluded_users' => $module->excludedUsers->map(function ($module) {
                    return $module->user;
                }),
                'created_at' => $module->created_at,
                'deleted_at' => $module->updated_at,
            ];
        });

        return [
            'data' => $this->collection,
            'meta' => [],
        ];
    }
}
