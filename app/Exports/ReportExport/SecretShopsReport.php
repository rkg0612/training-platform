<?php

namespace App\Exports\ReportExport;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SecretShopsReport implements FromView
{
    use Exportable;

    public $internetShops;
    public $carbon;

    public function __construct($internetShops)
    {
        $this->internetShops = $internetShops;
        $this->carbon = new Carbon();
    }

    public function view(): View
    {
        return view('exports.secret_shops_export', [
            'internetShops' => $this->internetShops,
            'carbon'    =>  $this->carbon,
        ]);
    }
}
