<?php

namespace App\Http\Controllers;

use App\Models\BomSale;
use App\Models\Sale;
use Illuminate\Http\Request;

class COGSController extends Controller
{
    public function getAll(){
        // $boms = Bom::with('bomItems')->get();
        // return response()->json($boms);
        
        $bomSales = BomSale::with('bomSaleItems')->withSum('salesItems','total')->get();

        foreach($bomSales as $bomSale){
            $bomSale->cogs = $bomSale->sales_items_sum_total;
            $cogs = $bomSale->cogs;
            $total = $bomSale->invoice_total;
            $margin = (1 - ($cogs/$total)) * 100;
            $bomSale->margin = (float)number_format($margin,2);
            // $bomSale->margin = $margin;
        }

        return response()->json($bomSales);
    }

    public function getById($id){
        // $boms = Bom::with('bomItems')->get();
        // return response()->json($boms);
        
        $bomSale = BomSale::with('bomSaleItems')->withSum('salesItems','total')->find($id);

        $bomSale->cogs = $bomSale->sales_items_sum_total;
        $cogs = $bomSale->cogs;
        $total = $bomSale->invoice_total;
        $margin = (1 - ($cogs/$total)) * 100;
        $bomSale->margin = (float)number_format($margin,2);

        return response()->json($bomSale);
    }
}
