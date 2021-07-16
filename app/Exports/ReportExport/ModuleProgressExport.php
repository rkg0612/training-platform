<?php

namespace App\Exports\ReportExport;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ModuleProgressExport implements WithMultipleSheets
{
    use Exportable;

    public $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->results as $result) {
            $sheets[] = new ModuleProgressExportSheet($result);
        }

        return $sheets;
    }
}
