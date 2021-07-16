<?php

namespace App\Exports\ReportExport;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;

class OverviewExportSheet implements FromView, WithTitle
{
    public $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function view(): View
    {
        return view('exports.overview_export', [
            'results' => $this->result,
        ]);
    }

    public function title(): string
    {
        return $this->result['name'];
    }
}
