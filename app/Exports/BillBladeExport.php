<?php

namespace App\Exports;

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
        $tasks = Task::all();
        return view('Templates.task', ['tasks'=>$tasks]);
    }
}
