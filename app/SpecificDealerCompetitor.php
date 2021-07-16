<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\SpecificDealerCompetitor.
 *
 * @property int $id
 * @property string $name
 * @property int|null $specific_dealer_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor findRecord($id, $name)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor newQuery()
 * @method static \Illuminate\Database\Query\Builder|SpecificDealerCompetitor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor whereSpecificDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealerCompetitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SpecificDealerCompetitor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SpecificDealerCompetitor withoutTrashed()
 * @mixin \Eloquent
 */
class SpecificDealerCompetitor extends Model
{
    use SoftDeletes;

    protected $table = 'specific_dealer_competitors';

    protected $fillable = [
        'name',
        'specific_dealer_id',
    ];

    public function scopeFindRecord($query, $id, $name)
    {
        if (is_int($name)) {
            return $query->where('id', $name)->get();
        }

        return $query->where([
            ['name', $name],
            ['specific_dealer_id', '=', $id],
        ])->get();
    }
}
