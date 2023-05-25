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


    public function edit($id)
    {
        // dd('hi index');
        $bill = Bill::with('billItems')->find($id);

        // Return the customers as a response
        return response()->json($bill);
    }


    public function update(Request $request, $id)
    {
        $billData = $request->only('description', 'date', 'invoice_total');
        $bill = Bill::find($id);
        $bill->update($billData);
        // dd('update', $request->all(), $billData, $bill);
        foreach($request->items as $item){
            $item['bill_id'] = $bill->id;
            if(isset($item['id'])){
                BillItem::find($item['id'])->update($item);
            } else {
                BillItem::create($item);
            }
        }
        return $bill;
    }

}
