<?php

namespace App\Services\LMS;

use App\Models\CourseDealer;

class CourseDealerService
{
    public function store($dealers, $id)
    {
        $dealers->each(function ($dealer) use ($id) {
            $courseDealer = new CourseDealer;
            $courseDealer->dealer_id = $dealer;
            $courseDealer->course_id = $id;
            $courseDealer->save();
        });
    }

    public function update($dealers, $id)
    {
        $records = CourseDealer::where('course_id', $id)->pluck('dealer_id');
        $records->each(function ($record) use ($dealers, $id) {
            if (! in_array($record, $dealers->toArray())) {
                CourseDealer::where('course_id', $id)->where('dealer_id', $record)->delete();
            }
        });

        $dealers->each(function ($dealer) use ($records, $id) {
            if (! in_array($dealer, $records->toArray())) {
                $courseDealer = new CourseDealer;
                $courseDealer->course_id = $id;
                $courseDealer->dealer_id = $dealer;
                $courseDealer->save();
            }
        });
    }
}
