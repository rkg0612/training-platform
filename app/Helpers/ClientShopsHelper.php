<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ClientShopsHelper
{
    public function builderQuery($builder, $request)
    {
        foreach ($request as $key => $value) {
            if (empty($value) || $value == 'noFilter') {
                continue;
            } else {
                if ($key == 'start_at') {
                    $builder->whereBetween($key, $value);
                } elseif ($key == 'specific_dealer_id') {
                    $builder->whereIn($key, $value);
                } else {
                    $builder->where($key, $value);
                }
            }
        }

        return $builder;
    }
}
