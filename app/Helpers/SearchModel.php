<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Trait SearchRelationship.
 */
trait SearchModel
{
    /**
     * function handleSearch
     * Search the model with relationship.
     */
    public function handleSearch()
    {
        Builder::macro('search', function ($attributes, string $keyword) {
            $this->where(function (Builder $query) use ($attributes, $keyword) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        // if the attributes contains relationship or '.'
                        Str::contains($attributes, '.'),
                        function (Builder $query) use ($attribute, $keyword) {
                            // destructing array attribute using shorthand
                            [ $relation, $column ] = explode('.', $attribute);

                            $query->orWhereHas($relation, function (Builder $query) use ($column, $keyword) {
                                $query->where($column, 'LIKE', "% {$keyword} %");
                            });
                        },
                        // else statement if the attributes doesn't contain '.'
                        function (Builder $query) use ($attribute, $keyword) {
                            $query->orWhere($attribute, 'LIKE', "% {$keyword} %");
                        }
                    );
                }
            });

            return $this;
        });
    }
}
