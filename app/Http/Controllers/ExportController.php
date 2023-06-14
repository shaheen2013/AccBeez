<?php

namespace App\Http\Controllers;

use App\Exports\BillBladeExport;
use App\Exports\BomBladeExport;
use App\Exports\SaleBladeExport;
use App\Exports\BillExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class ExportController extends Controller
{
    //

    // public function importXls(){
    //     // dd(request()->file('data'));
    //     $file = request()->file('data');
    //     $headingRow = (new HeadingRowImport(2))->toCollection($file)->first();
    //     $columnNames = $headingRow->toArray()[0];
    //     $expect = ['no','title','description','due','status'];
    //     for($i=0 ; $i< sizeof($expect);++$i){
    //         if($columnNames[$i] != $expect[$i]){
    //             return response()->json(['message'=>"column name $columnNames[$i] should be $expect[$i]"],400);
    //         }
    //     }
    //     try {
    //         Excel::import(new TaskImport,$file);
    //         return response()->json(['message'=>'Import Successful!'],200);
    //     } catch (\Throwable $th) {
    //         // throw $th;
    //         Log::error('importXls error at ',$th->getTrace());
    //         return response()->json(['message'=>'something wrong! Please try again'],400);
    //     }
    // }

    public function exportBillXls($invoiceId,$format){
        $exporter = new BillExport($invoiceId);
        $file_name = 'billinvoice_'.date('U').'.'.$format;
        return Excel::download($exporter, $file_name);
    }

    // public function exportCsv(){
        //     $file_name = 'tasks_'.date('U').'.csv';
        //     return Excel::download(new TaskExport, $file_name);
        // }

    public function exportBillBladeXls($invoiceId, $format){
        $exporter = new BillBladeExport($invoiceId);
        $file_name = 'Bill_'.date('U').'.'.$format;

        return Excel::download($exporter, $file_name);
    }


    public function exportSaleBladeXls($invoiceId, $format){
        $exporter = new SaleBladeExport($invoiceId);
        $file_name = 'Sale_'.date('U').'.'.$format;

        return Excel::download($exporter, $file_name);
    }


    public function exportBomBladeXls($invoiceId, $format){
        $exporter = new BomBladeExport($invoiceId);
        $file_name = 'Bom_'.date('U').'.'.$format;

        return Excel::download($exporter, $file_name);
    }


}
