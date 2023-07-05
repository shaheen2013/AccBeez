<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use Exception;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Http\Request;
use App\Http\Requests\BillRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
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

        $company_id = Company::where('slug', $searchParams['slug'])->pluck('id')->first();
        $billsQuery = DB::table('bills')
                        ->where('company_id', $company_id)
                        ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                            return $query->where('description', 'LIKE', '%' . $keyword . '%');
                        })->latest();

        return response()->json($billsQuery->paginate($limit));
    }

    public function store(BillRequest $request)
    {
        try {
            $billData = $request->only('description', 'date', 'invoice_total');
            DB::beginTransaction();
            $bill = Bill::create($billData);
            $bill->invoice_number = mt_rand(10000, 99999).'-'.$bill->id;
            $bill->save();
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
            foreach($request->deletedItemsID as $deletedID){
                $this->deleteItem($deletedID);
            }
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

    public function delete($id)
    {
        try {
            $bill = Bill::find($id);
            DB::beginTransaction();
            BillItem::where('bill_id', $id)->delete();
            $bill->delete();
            DB::commit();

            $bills = Bill::all();
            return response()->json($bills);
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function deleteItem($id)
    {
        $item = BillItem::find($id);
        if(isset($item)){
            $item->delete();
        }
    }

    public function downloadBillsInPdf()
    {
        $bill = Bill::latest()->get();

        $pdf = Pdf::loadView('bills.list', ['bills' => $bill]);
        // dd($pdf);
        return $pdf->download('bill-list.pdf');
    }

    public function downloadPdf($id)
    {

        $bill = Bill::with('billItems')->find($id);
        $user = Auth::user()->toArray();
        // dd($bill);
        $data = [
            'bill' => $bill,
        ];
        $pdf = Pdf::loadView('bills.invoice', ['bill' => $bill]);
        // dd($pdf);
        return $pdf->download('invoice.pdf');
    }


    public function bulkdelete(Request $request)
    {
        try {
            DB::beginTransaction();
            $billItems = BillItem::whereIn('bill_id', $request->all())->delete();
            Bill::whereIn('id', $request->all())->delete();
            DB::commit();

            return 'Bulk Deleted Bills';
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function exportData(){
        try{
            $data = BillResource::collection(Bill::latest()->get());

            return response()->successResponse('Bill list', $data);
        }catch (Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }

}
