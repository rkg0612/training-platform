<?php

namespace App\Services;

use App\Exports\ReportExport\GroupShopExport;
use App\Models\GroupShop;
use Carbon\Carbon;
use URL;

class ExportGroupShopReportService
{
    public function createLogs($id)
    {
        $groupShop = GroupShop::query()
            ->withTrashed()
            ->with([
                'phoneShops.dealer',
                'phoneShops.specificDealer',
                'phoneShops.city',
                'phoneShops.state',
                'phoneShops.secretShopper',
                'internetShops.dealer',
                'internetShops.specificDealer',
                'internetShops.competitor',
                'internetShops.city',
                'internetShops.state',
                'internetShops.postedBy',
                'internetShops.source',
            ])
            ->find($id);

        $path = 'group-shop-reports/';

        $fileName = 'Group-Shop-'.Carbon::now()->format('Y-m-d').'.xlsx';

        \Maatwebsite\Excel\Facades\Excel::store(new GroupShopExport($groupShop), $path.$fileName, 'local', null, [
            'visibility' => 'public',
        ]);

        return URL::temporarySignedRoute('export.group-shop', now()->addMinutes(3), [
            'fileName' => $fileName,
        ]);
    }
}
