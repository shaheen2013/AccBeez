<?php

namespace App\Http\Controllers;

use App\Http\Resources\BomResource;
use Exception;
use App\Models\Bom;
use App\Models\BomItem;
use Illuminate\Http\Request;
use App\Http\Requests\BomRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class BomController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        // dd('hi index', $searchParams);
        $limit = Arr::get($searchParams, 'limit', 5);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $bomsQuery = DB::table('boms')
                        ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                            return $query->where('name', 'LIKE', '%' . $keyword . '%');
                        })->latest();

        return response()->json($bomsQuery->paginate($limit));
    }

    public function store(BomRequest $request)
    {
        try {
            $bomData = $request->only('name', 'invoice_total');
            DB::beginTransaction();
            $bom = Bom::create($bomData);
            foreach($request->items as $item){
                $item['bom_id'] = $bom->id;
                BomItem::create($item);
            }
            DB::commit();
            return $bom;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function edit($id)
    {
        $bom = Bom::with('bomItems')->find($id);

        // Return the customers as a response
        return response()->json($bom);
    }


    public function update(BomRequest $request, $id)
    {
        try {
            $bomData = $request->only('name', 'invoice_total');
            $bom = Bom::find($id);
            DB::beginTransaction();
            $bom->update($bomData);
            foreach($request->deletedItemsID as $deletedID){
                $this->deleteItem($deletedID);
            }
            // dd('update', $request->all(), $bomData, $bom);
            foreach($request->items as $item){
                $item['bom_id'] = $bom->id;
                if(isset($item['id'])){
                    BomItem::find($item['id'])->update($item);
                } else {
                    BomItem::create($item);
                }
            }
            DB::commit();
            return $bom;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function delete($id)
    {
        try {
            $bom = Bom::find($id);
            DB::beginTransaction();
            BomItem::where('bom_id', $id)->delete();
            $bom->delete();
            DB::commit();

            $boms = Bom::all();
            return response()->json($boms);
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function deleteItem($id)
    {
        $item = BomItem::find($id);
        if(isset($item)){
            $item->delete();
        }
    }

    public function downloadPdf($id)
    {

        $bom = Bom::with('bomItems')->find($id);
        // dd($bom);
        $data = [
            'bom' => $bom,
        ];
        $pdf = Pdf::loadView('boms.invoice', ['bom' => $bom]);
        // dd($pdf);
        return $pdf->download('bom invoice.pdf');
    }


    public function bulkdelete(Request $request)
    {
        try {
            DB::beginTransaction();
            $bomItems = BomItem::whereIn('bom_id', $request->all())->delete();
            Bom::whereIn('id', $request->all())->delete();
            DB::commit();

            return 'Bulk Deleted Boms';
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function getAllBoms()
    {
        $boms = Bom::select('name', 'invoice_total')
                            ->orderBy('name', 'asc')
                            ->get();
        return response()->json($boms);
    }

    public function exportData(){
        try{
            $data = BomResource::collection(Bom::latest()->get());
            return response()->successResponse('Bom list', $data);
        }catch (Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }

    }
}
