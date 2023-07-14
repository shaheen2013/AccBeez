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
        // company average updated
        $products = BillItem::with(['closingDates' => function ($query) {
                                $query->select('id', 'sku', 'date')->orderBy('date', 'desc');
                            }])
                            ->where('company_id', $company_id)
                            ->select('sku', 'name', 'bill_items.id as id', 'unit', 'description', 'quantity', DB::raw('SUM(total) / SUM(quantity) as rate'))
                            ->groupBy('sku')
                            ->get();



        return response()->json($products);
    }
}
