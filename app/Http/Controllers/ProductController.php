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
        // $products = DB::table('bill_items')->unique('sku')->get();
        $products = DB::table('bill_items')->get()->unique('sku');

        return response()->json($products);
    }
}
