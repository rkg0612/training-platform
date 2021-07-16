<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QuizQuestion.
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\QuizQuestionAnswer[] $answers
 * @property-read int|null $answers_count
 * @property-read mixed $is_multiple
 * @property-read \App\Models\Quiz|null $question
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestion whereValue($value)
 * @mixin \Eloquent
 */
class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id', 'value',
    ];

    public $appends = [
        'is_multiple',
    ];

    public function question()
    {
        return $this->hasOne(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizQuestionAnswer::class);
    }

    public function getIsMultipleAttribute()
    {
        return $this->answers->where('is_correct', 1)->count() > 1 ? true : false;
    }
}
