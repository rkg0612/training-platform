<?php

namespace App\Exports\DailyUsageReport;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BigBossDailyUsageReport implements WithMultipleSheets
{
    use Exportable;

    public $dealers;

    public function __construct(Collection $dealers)
    {
        $this->dealers = $dealers;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        for ($ctr = 0; $ctr <= count($this->dealers) - 1; $ctr++) {
            $sheets[] = new DealerDailyUsageReport($this->dealers[$ctr]->get('users'), $this->dealers[$ctr]->get('name'));
        }

        return $sheets;
    }
}
