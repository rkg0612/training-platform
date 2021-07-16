<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\QuizQuestionAnswer.
 *
 * @property int $id
 * @property int $quiz_question_id
 * @property string|null $value
 * @property int $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\QuizQuestion $question
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer whereQuizQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswer whereValue($value)
 * @mixin \Eloquent
 */
class QuizQuestionAnswer extends Model
{
    protected $fillable = [
        'quiz_question_id', 'value', 'is_correct',
    ];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
