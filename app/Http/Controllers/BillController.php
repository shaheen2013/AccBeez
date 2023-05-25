<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Http\Request;
use App\Http\Requests\BillRequest;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        // dd('hi index');
        $bills = Bill::all();

        // Return the customers as a response
        return response()->json($bills);
    }

    public function store(BillRequest $request)
    {
        try {
            $billData = $request->only('description', 'date', 'invoice_total');
            DB::beginTransaction();
            $bill = Bill::create($billData);
            // dd('store', $request->all(), $billData);
            foreach($request->items as $item){
                $item['bill_id'] = $bill->id;
                BillItem::create($item);
            }
            DB::commit();
            return $bill;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function edit($id)
    {
        // dd('hi index');
        $bill = Bill::with('billItems')->find($id);

        // Return the customers as a response
        return response()->json($bill);
    }


    public function update(BillRequest $request, $id)
    {
        try {
            $billData = $request->only('description', 'date', 'invoice_total');
            $bill = Bill::find($id);
            DB::beginTransaction();
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
            DB::commit();
            return $bill;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

}
