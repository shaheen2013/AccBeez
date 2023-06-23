<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BalanceSheetBladeExport implements FromView,ShouldAutoSize
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        //$bill = Bill::with('billItems')->find($this->bill_id);
        return view('register.balance-sheet', $this->data);
    }
}
