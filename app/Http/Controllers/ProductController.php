<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $company_id = getCompanyIdBySlug($request->slug);
        $products = BillItem::with(['closingDates' => function ($query) {
                                $query->select('id', 'sku', 'date')->orderBy('date', 'desc');
                            }])
                            ->where('company_id', $company_id)
                            ->select('sku', 'name', 'rate', 'bill_items.id as id', 'unit', 'description', 'quantity')
                            ->orderBy('sku', 'asc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->unique('sku');

        return response()->json($products);
    }
}
