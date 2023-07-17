<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date' => 'required',
            // 'description' => 'nullable|string',
            // 'vendor_name' => 'nullable|string',
            // 'bill_number' => 'nullable|string',
            // 'items.0' => 'required|min:1',
            'sku' =>  'required|string',
            'value' =>  'required|numeric',
            // 'items.*.description' => 'nullable|string',
            'quantity' =>  'required|numeric',
        ]);
        $company_id = getCompanyIdBySlug($request->slug);
        try {
            $billData = $request->only('description', 'date', 'invoice_total', 'vendor_name', 'bill_number');
            $billData['company_id'] = $company_id;
            $billData['bill_number'] = 'BOI'. mt_rand(1000,9999);
            $billData['invoice_total'] = $request->value;
            // $billData['date'] = date("Y-m-d",strtotime(date("Y-m-").'01'));

            DB::beginTransaction();
            $bill = Bill::create($billData);
            $bill->invoice_number = mt_rand(10000, 99999).'-'.$bill->id;
            $bill->save();
            // dd('store', $request->all(), $billData);
            $item = [
                'name' => explode('-',$request->sku)[0],
                'sku' => $request->sku,
                'total' => $request->value, 
                'quantity' => $request->quantity
            ];
            $item['rate'] = $item['total']/$item['quantity'];
            $item['bill_id'] = $bill->id;
            $item['company_id'] = $company_id;
            $billItem = BillItem::create($item);
            $billData['item'] = $billItem;
            
            DB::commit();
            // return $bill;
            return response()->json(['status'=> true, 'data'=>$billData],200);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('InventoryController store method: ',$ex->getTrace());
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }
}
