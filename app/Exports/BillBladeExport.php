<?php

namespace App\Exports;

use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BillBladeExport implements FromView,ShouldAutoSize
{
    private $bill_id = null;

    public function __construct($bill_id)
    {
        $this->bill_id = $bill_id;
    }
    public function view(): View
    {
        $bill = Bill::with('billItems')->find($this->bill_id);
        return view('bills.invoice', ['bill'=>$bill]);
    }
}
