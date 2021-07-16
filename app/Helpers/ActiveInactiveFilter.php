<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

trait ActiveInactiveFilter
{
    public function allRecords()
    {
        Builder::macro('allRecords', function ($attributes) {
            $this->withTrashed($attributes);

            return $this;
        });
    }

    public function withoutTrashed()
    {
        Builder::macro('withoutTrashedRecords', function ($attributes) {
            $this->withoutTrashed($attributes);

            return $this;
        });
    }

    public function onlyTrashed()
    {
        Builder::macro('onlyInactiveRecords', function ($attributes) {
            $this->onlyTrashed($attributes);

            return $this;
        });
    }
}
