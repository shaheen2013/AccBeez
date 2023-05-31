<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Bom;
use App\Models\BomItem;
use Illuminate\Http\Request;
use App\Http\Requests\BomRequest;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    public function index()
    {
        // dd('hi index');
        $boms = Bom::all();

        // Return the customers as a response
        return response()->json($boms);
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
}
