<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CourseDealer.
 *
 * @property int $id
 * @property int $dealer_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\Dealer $dealer
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseDealer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseDealer extends pivot
{
    protected $table = 'course_dealer';

    protected $fillable = [
        'dealer_id', 'course_id',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
