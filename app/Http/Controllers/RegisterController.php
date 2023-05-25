<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index()
    {
        $registers = BillItem::all();

        $registers = DB::table('bill_items')
                   ->select('sku', DB::raw('SUM(quantity) as total_items'),
                                    DB::raw('SUM(total) as total_cost'),
                                    DB::raw('round(SUM(total) / SUM(quantity),2) as avg_cost')
                            )
                   ->groupBy('sku')
                   ->get()->toArray();
        // dd($registers);

        return response()->json($registers);
    }
}
