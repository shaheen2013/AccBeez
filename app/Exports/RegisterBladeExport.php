<?php

namespace App\Exports;

use App\Models\Bill;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegisterBladeExport implements FromView,ShouldAutoSize
{
    private $data;

    public function __construct($registerData)
    {
        $this->data = $registerData;
    }
    public function view(): View
    {
        //$bill = Bill::with('billItems')->find($this->bill_id);
        return view('register.export-data', $this->data);
    }
}
