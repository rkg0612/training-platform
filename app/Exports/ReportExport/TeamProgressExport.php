<?php

namespace App\Exports\ReportExport;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;

class TeamProgressExport implements FromView
{
    use Exportable;

    public $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function view(): View
    {
        return view('exports.team_progress', [
            'results' => $this->results,
        ]);
    }
}
