<?php

namespace App\Exports\DailyUsageReport;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DealerDailyUsageReport implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    public $users;

    public $dealerName;

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $headers = 'A1:K1';
                $event->sheet->getDelegate()->getStyle($headers)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('696969');
                $event->sheet->getDelegate()->getStyle($headers)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($headers)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($headers)->getFont()->setColor(Color::indexedColor(2));
            },
        ];
    }

    public function __construct($users, $dealerName)
    {
        $this->users = $users;
        $this->dealerName = $dealerName;
    }

    public function view(): View
    {
        return view('daily_usage.normal_report')
            ->with([
                'users' => $this->users,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function title(): string
    {
        return explode(' ', trim($this->dealerName))[0];
    }
}
