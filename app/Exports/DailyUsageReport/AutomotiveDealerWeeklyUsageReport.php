<?php

namespace App\Exports\DailyUsageReport;

use App\Models\SpecificDealer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AutomotiveDealerWeeklyUsageReport implements WithMultipleSheets
{
    use Exportable;

    public $specificDealersUsers;

    public $name;

    public function __construct(Collection $specificDealersUsers, string $name)
    {
        $this->specificDealersUsers = $specificDealersUsers;
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $keys = $this->specificDealersUsers->keys();
        $specificDealersUsers = $this->specificDealersUsers->toArray();

        for ($ctr = 0; $ctr <= count($keys) - 1; $ctr++) {
            $name = ! is_int($keys[$ctr]) ? $this->name : SpecificDealer::find($keys[$ctr])->name;
            $sheets[] = new DealerDailyUsageReport($specificDealersUsers[$keys[$ctr]], $name);
        }

        return $sheets;
    }
}
