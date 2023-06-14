<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaleBladeExport implements FromView,ShouldAutoSize
{
    private $sale_id = null;

    public function __construct($sale_id)
    {
        $this->sale_id = $sale_id;
    }
    public function view(): View
    {
        $sale = Sale::with('saleItems')->find($this->sale_id);
        return view('sales.invoice', ['sale'=>$sale]);
    }
}
