<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        // dd('hi index');
        $bills = Bill::all();

        // Return the customers as a response
        return response()->json($bills);
    }

    public function store(Request $request)
    {
        $billData = $request->only('description', 'date', 'invoice_total');
        // dd('store', $request->all(), $billData);
        $bill = Bill::create($billData);
        foreach($request->items as $item){
            $item['bill_id'] = $bill->id;
            BillItem::create($item);
        }
        return $bill;
    }
}
