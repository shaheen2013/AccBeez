<?php

namespace App\Exports;

use App\Models\Bill;
use App\Models\BillItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BillExport implements FromCollection,WithHeadings,WithHeadingRow
{
    private $bill_id = null;

    public function __construct($bill_id)
    {
        $this->bill_id = $bill_id;
    }
    public function collection()
    {
        $bill = [];
        $bill_items = [];
        if($this->bill_id){
            $bill_items = BillItem::where('bill_id',$this->bill_id)->get();
        }
        $data = $bill_items->map(function($bill_item,$key){
            return [
                'Sku'=>$bill_item->sku,
                'quantity'=>$bill_item->quantity,
                'unit'=>$bill_item->unit,
                'rate'=>$bill_item->rate,
                'total'=> $bill_item->total,
            ];
        });
        return $data ;
    }

    public function headings():array
    {
        return [
            'SKU',
            'Quantity',
            'Unit',
            'Price',
            'Total',
        ];
    }
}
