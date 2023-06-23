<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use App\Http\Resources\SalesResource;
use App\Models\Bill;
use Exception;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\SaleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $searchParams = $request->all();
        // dd('hi index', $searchParams);
        $limit = Arr::get($searchParams, 'limit', 5);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $salesQuery = DB::table('sales')
                        ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                            return $query->where('description', 'LIKE', '%' . $keyword . '%');
                        })->latest();

        return response()->json($salesQuery->paginate($limit));
    }

    public function store(SaleRequest $request)
    {
        try {
            $saleData = $request->only('description', 'date', 'invoice_total');
            DB::beginTransaction();
            $sale = Sale::create($saleData);
            $now = Carbon::now();
            $unique_code = $now->format('u');
            // $unique_code = mt_rand(10000,99999);
            $sale->invoice_number = $unique_code.'-'.$sale->id;
            $sale->save();
            // dd('store', $request->all(), $saleData);
            foreach($request->items as $item){
                $item['sale_id'] = $sale->id;
                SaleItem::create($item);
            }
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
            $saleData = $request->only('description', 'date', 'invoice_total');
            $sale = Sale::find($id);
            DB::beginTransaction();
            $sale->update($saleData);
            foreach($request->deletedItemsID as $deletedID){
                $this->deleteItem($deletedID);
            }
            // dd('update', $request->all(), $saleData, $sale);
            foreach($request->items as $item){
                $item['sale_id'] = $sale->id;
                if(isset($item['id'])){
                    SaleItem::find($item['id'])->update($item);
                } else {
                    SaleItem::create($item);
                }
            }
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

            $sales = Sale::all();
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
        ];
        $pdf = Pdf::loadView('sales.invoice', ['sale' => $sale]);
        // dd($pdf);
        return $pdf->download('invoice.pdf');
    }


    public function bulkdelete(Request $request)
    {
        try {
            DB::beginTransaction();
            $saleItems = SaleItem::whereIn('sale_id', $request->all())->delete();
            Sale::whereIn('id', $request->all())->delete();
            DB::commit();

            return 'Bulk Deleted Sales';
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function exportData(){
        try{
            $data = SalesResource::collection(Sale::latest()->get());

            return response()->successResponse('Sales list', $data);
        }catch (Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }

}
