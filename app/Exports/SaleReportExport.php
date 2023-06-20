<?php

namespace App\Exports;
use App\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleReportExport implements FromView
{
    private $dateStart;
    private $dateEnd;
    private $totalSale;
    private $sales;

    public function __construct($date_start, $date_end){
        $dateStart = date("Y-m-d H:i:s", strtotime($date_start));
        $dateEnd = date("Y-m-d H:i:s", strtotime($date_end));
        $sales = Sale::whereBetween('updated_at', [$dateStart, $dateEnd])->where('sale_status','paid');
        $totalSale = $sales->sum('total_price');
        $this->dateStart = $date_start;
        $this->dateEnd = $date_end;
        $this->sales = $sales;
        $this->totalSale = $totalSale;
    }

    public function view(): View
    {
        return view('Exports.SaleReport', [
            'Sales' => $this->sales,
            'totalSale' => $this->totalSale,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd
        ]);
    }
}
