<?php

namespace App\Http\Controllers;

use App\Exports\BalanceSheetBladeExport;
use App\Exports\CogsBladeExport;
use App\Models\BomSale;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class COGSController extends Controller
{
    public function getAll(){
        // $boms = Bom::with('bomItems')->get();
        // return response()->json($boms);

        $bomSales = BomSale::with('bomSaleItems.saleItems')->get();
        $data = [];

        foreach($bomSales as $bomSale){
            foreach($bomSale->bomSaleItems as $bomSaleItem){
                $sum = 0;
                foreach ($bomSaleItem->saleItems as $saleItem) {
                    $sum += $saleItem['total'];
                }

                $cogs = $sum;
                $total = $bomSaleItem->total;
                $margin = (1 - ($cogs/$total)) * 100;
                $bomSaleItem->margin = (float)number_format($margin,2);
                $data[] = [
                    'id'=>$bomSale->id,
                    'date'=>$bomSale->date,
                    'description'=>$bomSale->description,
                    'invoice_number'=>$bomSale->invoice_number,
                    'invoice_total'=>$bomSale->invoice_total,
                    'name'=> $bomSaleItem->name,
                    'rate'=> $bomSaleItem->rate,
                    'unit'=> $bomSaleItem->unit,
                    'quantity'=> $bomSaleItem->quantity,
                    'total'=> $bomSaleItem->total,
                    'cogs'=>$cogs,
                    'margin'=>$bomSaleItem->margin,
                ];
            }
            // $bomSale->margin = $margin;
        }

        return response()->json($data);
    }

    public function getById($id){
        // $boms = Bom::with('bomItems')->get();
        // return response()->json($boms);

        $bomSale = BomSale::with('bomSaleItems.saleItems')->find($id);

        $bomSale->cogs = $bomSale->sales_items_sum_total;
        $cogs = $bomSale->cogs;
        $total = $bomSale->invoice_total;
        $margin = (1 - ($cogs/$total)) * 100;
        $bomSale->margin = (float)number_format($margin,2);

        return response()->json($bomSale);
    }

    public function exportData(Request $request){

        $bomSales = BomSale::with('bomSaleItems.saleItems')->get();
        $data = [];

        foreach($bomSales as $bomSale){
            foreach($bomSale->bomSaleItems as $bomSaleItem){
                $sum = 0;
                foreach ($bomSaleItem->saleItems as $saleItem) {
                    $sum += $saleItem['total'];
                }

                $cogs = $sum;
                $total = $bomSaleItem->total;
                $margin = (1 - ($cogs/$total)) * 100;
                $bomSaleItem->margin = (float)number_format($margin,2);
                $data[] = [
                    'id'=>$bomSale->id,
                    'date'=>$bomSale->date,
                    'description'=>$bomSale->description,
                    'invoice_number'=>$bomSale->invoice_number,
                    'invoice_total'=>$bomSale->invoice_total,
                    'name'=> $bomSaleItem->name,
                    'rate'=> $bomSaleItem->rate,
                    'unit'=> $bomSaleItem->unit,
                    'quantity'=> $bomSaleItem->quantity,
                    'total'=> $bomSaleItem->total,
                    'cogs'=>$cogs,
                    'margin'=>$bomSaleItem->margin,
                ];
            }
            // $bomSale->margin = $margin;
        }


        if($request->fileType === 'pdf'){
            $pdf = Pdf::loadView('cogs.export-data', ['cogs'=>$data])->setPaper('a4', 'landscape');
            return $pdf->download('cogs-list.pdf');
        }else{
            $exporter = new CogsBladeExport($data);
            $file_name = 'Register_'.date('U').'.'.$request->fileType;
            return Excel::download($exporter, $file_name);
        }
    }
}
