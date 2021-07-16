<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($category) {
            return (object) [
                'id' => $category->id,
                'value' => $category->value,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'assigned_courses' => $category->courses->map(function ($categoryCourse) {
                    return $categoryCourse->course->value;
                })->sort()->implode(', '),
                'banner_link' => $category->banner_link,
                'courses' => $category->courses,
                'excluded_users' => $category->excludedUsers,
            ];
        });

        return [
            'data' => $this->collection,
            'meta' => [],
        ];
    }
}
