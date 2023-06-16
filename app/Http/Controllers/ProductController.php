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
        $products = BillItem::with(['closingDates' => function ($query) {
                                $query->select('id', 'sku', 'date')->orderBy('date', 'desc');
                            }])
                            ->select('sku', 'name', 'rate', 'bill_items.id as id')
                            ->orderBy('sku', 'asc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->unique('sku');

        return response()->json($products);
    }
}
