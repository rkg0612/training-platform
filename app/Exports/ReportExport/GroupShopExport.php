<?php

namespace App\Exports\ReportExport;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class GroupShopExport implements FromView
{
    use Exportable;

    private $groupShop;

    public function __construct($groupShop)
    {
        $this->groupShop = $groupShop;
    }

    public function view(): View
    {
        return view('exports.group_shop_export', [
            'groupShop' => $this->groupShop,
        ]);
    }
}
