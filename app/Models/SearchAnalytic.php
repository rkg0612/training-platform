<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SearchAnalytic.
 *
 * @property int $id
 * @property int $user_id
 * @property int $dealer_id
 * @property string $model_type
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic query()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchAnalytic whereUserId($value)
 * @mixin \Eloquent
 */
class SearchAnalytic extends Model
{
    //
}
