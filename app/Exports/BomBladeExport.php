<?php

namespace App\Exports;

use App\Models\Bom;
use App\Models\BomItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BomBladeExport implements FromView,ShouldAutoSize
{
    private $bom_id = null;

    public function __construct($bom_id)
    {
        $this->bom_id = $bom_id;
    }
    public function view(): View
    {
        $bom = Bom::with('bomItems')->find($this->bom_id);
        return view('boms.invoice', ['bom'=>$bom]);
    }
}
