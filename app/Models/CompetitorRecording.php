<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\CompetitorRecording.
 *
 * @property int $id
 * @property int $phone_shop_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $label
 * @property string $storage_disk
 * @property-read mixed $playable_audio
 * @property-read \App\Models\PhoneShop $phoneShop
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording newQuery()
 * @method static \Illuminate\Database\Query\Builder|CompetitorRecording onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording wherePhoneShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereStorageDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompetitorRecording whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|CompetitorRecording withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CompetitorRecording withoutTrashed()
 * @mixin \Eloquent
 */
class CompetitorRecording extends Model
{
    use SoftDeletes;

    protected $appends = ['playable_audio'];

    protected $fillable = [
        'phone_shop_id', 'value', 'duration', 'label', 'storage_disk',
    ];

    public function phoneShop()
    {
        return $this->belongsTo(PhoneShop::class);
    }

    public function getPlayableAudioAttribute()
    {
        if ($this->storage_disk === 'local') {
            return asset(Storage::disk($this->storage_disk)->url("/phone_shops/{$this->phoneShop()->withTrashed()->first()->id}/competitor-recordings/{$this->value}"));
        }

        return Storage::disk($this->storage_disk)->url("phone_shops/{$this->phoneShop()->withTrashed()->first()->id}/competitor-recordings/{$this->value}");
    }
}
