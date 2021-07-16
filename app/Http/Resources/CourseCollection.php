<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection = $this->collection->map(function ($course) {
            return (object) [
                'id' => $course->id,
                'name' => $course->name,
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
                'deleted_at' => $course->deleted_at,
                'dealer' => $course->dealer,
            ];
        });

        return [
            'data' => $this->collection,
            'meta' => [],
        ];
    }
}
