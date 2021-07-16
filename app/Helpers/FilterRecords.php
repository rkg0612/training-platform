<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class FilterRecords.
 */
class FilterRecords
{
    /**
     * @const INACTIVE
     * Inactive records
     */
    const INACTIVE = 'inactive';

    /**
     * @const ACTIVE
     * Active records
     */
    const ACTIVE = 'active';

    /**
     * Model Class.
     */
    private $model;
    /**
     * Collections.
     */
    private $filter;
    /**
     * Array relationships.
     */
    private $relationships = [];
    /**
     * String keyword.
     */
    private $keyword;
    /**
     * Array search columns.
     */
    private $searchColumns = [];

    public function __construct(Model $model, Collection $filter, array $relationships, array $searchColumns, string $keyword = null)
    {
        $this->model = $model;
        $this->filter = $filter;
        $this->relationships = $relationships;
        $this->keyword = $keyword;
        $this->searchColumns = $searchColumns;
    }

    /**
     * @param  Model  $model
     * @param  Collection  $filter
     * @param  array  $relationships
     * @param  array  $searchColumns
     * @param  string  $keyword
     * @return $this
     */
    public static function make(Model $model, Collection $filter, array $relationships, array $searchColumns, string $keyword = null)
    {
        return new self(
            $model,
            $filter,
            $relationships,
            $searchColumns,
            $keyword
        );
    }

    public function get()
    {
        $filter = $this->filter->map(function ($filter) {
            return Str::lower($filter);
        })->toArray();

        if (in_array(self::ACTIVE, $filter) && in_array(self::INACTIVE, $filter)) {
            $this->model = $this->model->allRecords($this->relationships);
        } elseif (in_array(self::INACTIVE, $filter) && ! in_array(self::ACTIVE, $filter)) {
            $this->model = $this->model->onlyInactiveRecords($this->relationships);
        } elseif (! in_array(self::INACTIVE, $filter) && in_array(self::ACTIVE, $filter)) {
            $this->model = $this->model->withoutTrashedRecords($this->relationships);
        }

        if (! empty($this->keyword) && $this->keyword !== 'undefined') {
            $attributes = Arr::wrap($this->searchColumns);
            $keyword = "%{$this->keyword}%";

            foreach ($attributes as $attribute) {
                if (Str::contains($attribute, '.')) {
                    [$table, $column] = explode('.', $attribute);

                    $this->model = $this->model->orWhereHas($table, function (Builder $query) use ($column, $keyword) {
                        $query->where($column, 'LIKE', $keyword);
                    });
                } else {
                    $this->model = $this->model->orWhere($attribute, 'LIKE', $keyword);
                }
            }
        }

        return $this->model;
    }
}
