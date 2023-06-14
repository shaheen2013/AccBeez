<?php

namespace App\Http\Controllers;

use App\Http\Requests\BomSaleRequest;
use App\Models\BomSale;
use App\Models\BomSaleItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BomSaleController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        // dd('hi index', $searchParams);
        $limit = Arr::get($searchParams, 'limit', 5);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $bomSalesQuery = DB::table('bom_sales')
                        ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                            return $query->where('description', 'LIKE', '%' . $keyword . '%');
                        });

        return response()->json($bomSalesQuery->paginate($limit));
    }

    public function store(BomSaleRequest $request)
    {
        try {
            $bomSaleData = $request->only('description', 'date', 'invoice_total');
            DB::beginTransaction();
            $bomSale = BomSale::create($bomSaleData);
            $bomSale->invoice_number = $bomSale->id;
            $bomSale->save();
            // dd('store', $request->all(), $bomSaleData);
            foreach($request->items as $item){
                $item['bom_sale_id'] = $bomSale->id;
                BomSaleItem::create($item);
            }
            DB::commit();
            return $bomSale;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function edit($id)
    {
        // dd('hi index');
        $bomSale = BomSale::with('bomSaleItems')->find($id);

        // Return the customers as a response
        return response()->json($bomSale);
    }


    public function update(BomSaleRequest $request, $id)
    {
        try {
            $bomSaleData = $request->only('description', 'date', 'invoice_total');
            $bomSale = BomSale::find($id);
            DB::beginTransaction();
            $bomSale->update($bomSaleData);
            foreach($request->deletedItemsID as $deletedID){
                $this->deleteItem($deletedID);
            }
            // dd('update', $request->all(), $bomSaleData, $bomSale);
            foreach($request->items as $item){
                $item['bom_sale_id'] = $bomSale->id;
                if(isset($item['id'])){
                    BomSaleItem::find($item['id'])->update($item);
                } else {
                    BomSaleItem::create($item);
                }
            }
            DB::commit();
            return $bomSale;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $bomSale = BomSale::find($id);
            DB::beginTransaction();
            BomSaleItem::where('bom_sale_id', $id)->delete();
            $bomSale->delete();
            DB::commit();

            $bomSales = BomSale::all();
            return response()->json($bomSales);
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function deleteItem($id)
    {
        $item = BomSaleItem::find($id);
        if(isset($item)){
            $item->delete();
        }
    }

    public function downloadPdf($id)
    {

        $bomSale = BomSale::with('bomSaleItems')->find($id);
        $user = Auth::user();
        // dd($bomSale);
        $data = [
            'bomSale' => $bomSale,
        ];
        $pdf = Pdf::loadView('sales.invoice', ['sale' => $bomSale]);
        // dd($pdf);
        return $pdf->download('invoice.pdf');
    }


    public function bulkdelete(Request $request)
    {
        try {
            DB::beginTransaction();
            $bomSaleItems = BomSaleItem::whereIn('bom_sale_id', $request->all())->delete();
            BomSale::whereIn('id', $request->all())->delete();
            DB::commit();

            return 'Bulk Deleted Sales';
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }
}
