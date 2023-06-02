<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('auth');
    // }

    public function index()
    {
        $sales = Sale::with('bom')->get();
        return response()->json($sales);
    }

    public function store(SaleRequest $request)
    {
        try {
            $saleData = $request->only('date', 'amount', 'bom_id');
            DB::beginTransaction();
            $sale = Sale::create($saleData);
            // foreach($request->items as $item){
            //     $item['sale_id'] = $sale->id;
            //     SaleItem::create($item);
            // }
            DB::commit();
            return $sale;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function edit($id)
    {
        // dd('hi index');
        $sale = Sale::with('saleItems')->find($id);

        // Return the customers as a response
        return response()->json($sale);
    }


    public function update(SaleRequest $request, $id)
    {
        try {
            $saleData = $request->only('date', 'amount', 'bom_id');
            $sale = Sale::find($id);
            DB::beginTransaction();
            $sale->update($saleData);
            // foreach($request->deletedItemsID as $deletedID){
            //     $this->deleteItem($deletedID);
            // }
            // // dd('update', $request->all(), $saleData, $sale);
            // foreach($request->items as $item){
            //     $item['sale_id'] = $sale->id;
            //     if(isset($item['id'])){
            //         SaleItem::find($item['id'])->update($item);
            //     } else {
            //         SaleItem::create($item);
            //     }
            // }
            DB::commit();
            return $sale;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $sale = Sale::find($id);
            DB::beginTransaction();
            SaleItem::where('sale_id', $id)->delete();
            $sale->delete();
            DB::commit();

            $sales = Sale::with('bom')->get();
            return response()->json($sales);
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function deleteItem($id)
    {
        $item = SaleItem::find($id);
        if(isset($item)){
            $item->delete();
        }
    }

    public function downloadPdf($id)
    {

        $sale = Sale::with('saleItems')->find($id);
        $user = Auth::user()->toArray();
        // dd($sale);
        $data = [
            'sale' => $sale,
            'name' => 'John',
            'data' => 'hello',
        ];
        $pdf = Pdf::loadView('sales.invoice', ['sale' => $sale]);
        // dd($pdf);
        return $pdf->download('invoice.pdf');
    }


}
