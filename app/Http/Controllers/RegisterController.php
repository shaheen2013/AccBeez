<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 5);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $perPage = $request->input('limit') ?? 10;
        $page = $request->input('page') ?? 1;
        $startAt = ($perPage * ($page-1));
        // dd($perPage, $page, $startAt);

        $distinctMonths = DB::table('bills')
                            ->select(DB::raw("DATE_FORMAT(bills.date, '%m-%Y') as month"))
                            ->orderBy('month')
                            ->pluck('month', 'month')
                            ->unique()
                            ->toArray();

        $registers = DB::table('bill_items')
                        ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
                        ->select('sku', 'name', 'bill_items.id as bill_item_id', DB::raw('SUM(quantity) as total_items'),
                                            DB::raw('SUM(total) as total_cost'),
                                            DB::raw('round(SUM(total) / SUM(quantity),2) as avg_cost'),
                                            DB::raw('YEAR(bills.date) as year'),
                                            // DB::raw('MONTH(bills.date) as month')
                                            DB::raw("DATE_FORMAT(bills.date, '%m-%Y') as month")
                                            
                        )
                        ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                            return $query->where('sku', 'LIKE', '%' . $keyword . '%');
                        })
                        ->groupBy(['sku', 'month', 'year'])
                        ->orderBy('sku')
                        ->orderBy('year')
                        ->orderBy('month')
                        ->get();
        // $registers = $registerQuery->paginate($limit);

        $grouped = $registers->mapToGroups(function ($item) {
            return [$item->sku => [
                "bill_item_id" => $item->bill_item_id,
                "sku" => $item->sku,
                "name" => $item->name,
                "avg_cost" => $item->avg_cost,
                "month" => $item->month,
                "year" => $item->year,
            ]];
        });

        $simpleList = $grouped->map(function ($items) use ($distinctMonths) {
            $groupedItemsByMonth = $items->keyBy('month');
            $outputItem['sku'] = $items[0]['sku'];
            $outputItem['name'] = $items[0]['name'];
            $outputItem['bill_item_id'] = $items[0]['bill_item_id'];

            foreach ($distinctMonths as $month) {
                if ($groupedItemsByMonth->has($month)) {
                    $outputItem['month-'.$month] = $groupedItemsByMonth[$month]['avg_cost'];
                } else {
                    $outputItem['month-'.$month] = null;
                }
            }
            return $outputItem;
        })->toArray();
        // $spliced = array_splice($simpleList, $startAt, $perPage);
        // dd($spliced);

        $data = [
            'distinct_months' => $distinctMonths,
            'grouped' => $grouped,
            'total' => count($simpleList),
            'register_list' => array_values(array_splice($simpleList, $startAt, $perPage)),
            // 'register_list' => array_values($simpleList),
        ];

        return response()->json($data);
    }




    public function view($id)
    {
        $item = BillItem::find($id);
        $sku = $item->sku;
        $billItems = DB::table('bill_items')
                        ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
                        ->select('bill_items.quantity as bill_item_quantity', 'bill_items.rate as bill_item_rate',
                                'bill_items.total as bill_item_total', 'bills.date'
                        )
                        ->where('sku', $sku)
                        // ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        $saleItems = DB::table('sale_items')
                        ->leftJoin('sales', 'sale_items.sale_id', '=', 'sales.id')
                        ->select(
                            'sale_items.quantity as sale_item_quantity', 
                            'sale_items.rate as sale_item_rate',
                                // DB::raw('SUM(quantity) as sale_item_quantity'), 
                                // DB::raw('round(SUM(quantity) * SUM(rate),2) as sale_item_total'),
                                'sale_items.total as sale_item_total', 
                                'sales.date'
                        )
                        ->where('sku', $sku)
                        ->orderBy('date')
                        ->get();

        $mergedItems = [];
        $singleSaleItem = [
            "sale_item_quantity" => null,
            "sale_item_rate" => null,
            "sale_item_total" => null,
        ];
        // dd($saleItems);
        foreach ($billItems as $billItem) {
            $date = $billItem->date;
            $matchingSaleItem = collect($saleItems)->where('date', $date)->first() ??  [];
            $billItemArray = get_object_vars($billItem);
            // dd($billItemArray, $matchingSaleItem, $singleSaleItem);
            if($matchingSaleItem){
                $matchingSaleItemArray = (array)$matchingSaleItem;
                $mergedItem = array_merge($billItemArray, $matchingSaleItemArray);
                $mergedItems[] = $mergedItem;
            }else{
                $mergedItem = array_merge($billItemArray, $singleSaleItem);
                $mergedItems[] = $mergedItem;
            }
        }

        // dd($mergedItems);
        $singleBillItem = [
            "bill_item_quantity" => null,
            "bill_item_rate" => null,
            "bill_item_total" => null,
        ];
        foreach ($saleItems as $saleItem) {
            $date = $saleItem->date;
            $matchingBillItem = collect($billItems)->where('date', $date)->first() ??  [];
            $saleItemArray = get_object_vars($saleItem);
            // dd($saleItem, $matchingBillItem);
            if(!$matchingBillItem){
                $mergedItem = array_merge($saleItemArray, $singleBillItem);
                $mergedItems[] = $mergedItem;
            }
        }
        // dd($mergedItems);
        $mergedItems = collect($mergedItems)->sortBy('date')->values()->all();
        $data = [
            'mergedItems' => $mergedItems,
            'bill_item' => $item,
        ];
        return $data;
    }



    // public function index(Request $request)
    // {
    //     $searchParams = $request->all();
    //     $limit = Arr::get($searchParams, 'limit', 5);
    //     $keyword = Arr::get($searchParams, 'keyword', '');
    //     $perPage = $request->input('limit') ?? 10;
    //     $page = $request->input('page') ?? 1;
    //     $startAt = ($perPage * $page)-1;

    //     $distinctMonths = DB::table('bills')
    //                         ->select(DB::raw('MONTH(bills.date) as month'))
    //                         ->orderBy('month')
    //                         ->pluck('month', 'month')
    //                         ->unique()
    //                         ->toArray();

    //     $registerQuery = DB::table('bill_items')
    //                     ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
    //                     ->select('sku', 'name', 'bill_items.id as bill_item_id', DB::raw('SUM(quantity) as total_items'),
    //                                         DB::raw('SUM(total) as total_cost'),
    //                                         DB::raw('round(SUM(total) / SUM(quantity),2) as avg_cost'),
    //                                         DB::raw('YEAR(bills.date) as year'),
    //                                         DB::raw('MONTH(bills.date) as month')
    //                     )
    //                     ->when(!empty($keyword), function (Builder $query) use ($keyword) {
    //                         return $query->where('sku', 'LIKE', '%' . $keyword . '%');
    //                     })
    //                     ->groupBy(['sku', 'month', 'year'])
    //                     ->orderBy('sku')
    //                     ->orderBy('year')
    //                     ->orderBy('month');
    //                     // ->get();

    //     $registers = json_decode(json_encode($registerQuery->paginate($limit)));

    //     dd($registers);

    //     $grouped = collect($registers->data)->mapToGroups(function ($item) {
    //         return [$item->sku => [
    //             "bill_item_id" => $item->bill_item_id,
    //             "sku" => $item->sku,
    //             "name" => $item->name,
    //             "avg_cost" => $item->avg_cost,
    //             "month" => $item->month,
    //             "year" => $item->year,
    //         ]];
    //     });



    //     $simpleList = $grouped->map(function ($items) use ($distinctMonths) {
    //         $groupedItemsByMonth = $items->keyBy('month');
    //         $outputItem['sku'] = $items[0]['sku'];
    //         $outputItem['name'] = $items[0]['name'];
    //         $outputItem['bill_item_id'] = $items[0]['bill_item_id'];

    //         foreach ($distinctMonths as $month) {
    //             if ($groupedItemsByMonth->has($month)) {
    //                 $outputItem['month-'.$month] = $groupedItemsByMonth[$month]['avg_cost'];
    //             } else {
    //                 $outputItem['month-'.$month] = null;
    //             }
    //         }
    //         return $outputItem;
    //     })->toArray();

    //     $registers->data = $simpleList;


    //     $data = [
    //         'distinct_months' => $distinctMonths,
    //         'grouped' => $grouped,
    //         // 'register_list' => array_values(array_splice($simpleList, $startAt, $perPage)),
    //         'register_list' => $registers,
    //         // 'current_page' => $simpleList->currentPage(),
    //         // 'per_page' => $simpleList->perPage(),
    //         // 'total' => $simpleList->total(),
    //     ];


    //     return response()->json($data);
    // }
}
