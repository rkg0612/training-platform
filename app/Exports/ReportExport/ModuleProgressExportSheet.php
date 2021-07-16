<?php

namespace App\Exports\ReportExport;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;

class ModuleProgressExportSheet implements FromView, WithTitle
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function view(): View
    {
        return view('exports.module_progress', [
            'user' => $this->user,
        ]);
    }

    public function title(): string
    {
        return $this->user['name'];
    }
}
