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
                                $query->select('id', 'Sku', 'date')->orderBy('date', 'desc');
                            }])
                            ->select('Sku', 'name', 'rate', 'bill_items.id as id')
                            ->orderBy('Sku', 'asc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->unique('Sku');

        return response()->json($products);
    }
}
