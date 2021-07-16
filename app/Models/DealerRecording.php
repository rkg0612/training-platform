<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\DealerRecording.
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
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording newQuery()
 * @method static \Illuminate\Database\Query\Builder|DealerRecording onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording query()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording wherePhoneShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereStorageDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerRecording whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|DealerRecording withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DealerRecording withoutTrashed()
 * @mixin \Eloquent
 */
class DealerRecording extends Model
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
            return asset(Storage::disk($this->storage_disk)->url("public/phone_shops/{$this->phoneShop()->withTrashed()->first()->id}/dealer-recordings/{$this->value}"));
        }

        return Storage::disk($this->storage_disk)->url("phone_shops/{$this->phoneShop()->withTrashed()->first()->id}/dealer-recordings/{$this->value}");
    }
}
