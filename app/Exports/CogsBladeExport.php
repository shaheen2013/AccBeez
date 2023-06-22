<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CogsBladeExport implements FromView,ShouldAutoSize
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        //$bill = Bill::with('billItems')->find($this->bill_id);
        return view('cogs.export-data', ['cogs'=>$this->data]);
    }
}
