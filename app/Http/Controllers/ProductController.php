<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // $products = DB::table('bill_items')->get()->toArray();
        $products = DB::table('bill_items')->get()->unique('sku')->toArray();
        // $products = DB::table('bill_items')->get()->unique('sku')->map(function ($item) {
        //     return (array) $item;
        // })->toArray();
        $products = DB::table('bill_items')
                    ->select('sku', 'rate', 'id')
                    ->orderBy('sku', 'asc')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->unique('sku');
        // $products = DB::table('bill_items')
        //             ->select('sku', 'quantity', 'rate', 'total')
        //             // ->orderBy('rate', 'desc')
        //             ->distinct('sku')
        //             ->get()
        //             ->toArray();
        return response()->json($products);
    }
}
