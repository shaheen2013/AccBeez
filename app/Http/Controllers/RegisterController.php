<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Carbon\Carbon;
use App\Models\BillItem;
use App\Models\ClosingDate;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

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




    public function view1($id)
    {
        $item = BillItem::with('closingDates')->find($id);
        $closingDates = $item->closingDates;
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
                        // ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        $closingDates = DB::table('closing_dates')
                        ->select('date', 'sku')
                        ->where('sku', $sku)
                        ->orderBy('date')
                        ->get();

        $mergedItems = [];
        $singleSaleItem = [
            "sale_item_quantity" => null,
            "sale_item_rate" => null,
            "sale_item_total" => null,
        ];
        $singleBillItem = [
            "bill_item_quantity" => null,
            "bill_item_rate" => null,
            "bill_item_total" => null,
        ];
        $singleClosingDate = [
            "closing_date_quantity" => null,
            "closing_date_rate" => null,
            "closing_date_total" => null,
        ];

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


    public function close(Request $request)
    {
        // $request['date'] = Carbon::now()->format('Y-m-d');
        $input = $request->all();
        $input['date'] = '2023-02-10';
        $input['status'] = 0;
        $closingDate = ClosingDate::updateOrCreate(
            ['date' => $input['date'], 'sku' => $input['sku']],
            ['status' => 0]
        );
    }

    public function undo(Request $request)
    {
        // $request['date'] = Carbon::now()->format('Y-m-d');
        $input = $request->all();
        $closingDate = ClosingDate::where('sku', $input['sku'])->orderBy('created_at', 'desc')->first()->delete();
        // dd($closingDate, $input);
    }




    public function view($id)
    {
        $bill_item = BillItem::with('closingDates')->find($id);
        $closingDates = $bill_item->closingDates;
        $sku = $bill_item->sku;

        // Find unique dates in BillItem
        $uniqueDates = [];
        $billItemDates = Bill::leftJoin('bill_items', 'bills.id', '=', 'bill_items.bill_id')
                                ->groupBy('date')
                                ->select('bills.date', 'bill_items.sku', DB::raw("'billItem' as model"),
                                        DB::raw('SUM(quantity) as bill_item_quantity'),
                                        DB::raw('SUM(total) as bill_item_total'),
                                        DB::raw('SUM(total) / SUM(quantity) as bill_item_rate'),
                                        DB::raw('0 as bill_item_avg_rate'),
                                )
                                ->where('sku', $sku)
                                ->distinct('date')
                                ->get()
                                ->keyBy('date')
                                ->toArray();
        $saleItemDates = Sale::leftJoin('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                                ->groupBy('date')
                                ->select('sales.date', 'sale_items.sku', DB::raw("'saleItem' as model"),
                                        DB::raw('SUM(quantity) as sale_item_quantity'),
                                        DB::raw('SUM(total) as sale_item_total'),
                                        DB::raw('SUM(total) / SUM(quantity) as sale_item_rate'),
                                        DB::raw('0 as sale_item_avg_rate'),
                                )
                                ->where('sku', $sku)
                                ->distinct('date')
                                ->get()
                                ->keyBy('date')
                                ->toArray();
        $closingDates = ClosingDate::select('date', 'sku', DB::raw("'closingDate' as model"),
                                                DB::raw('0 as closing_date_avg_rate')
                                        )
                                        ->where('sku', $sku)
                                        ->distinct('date')
                                        ->get()
                                        ->keyBy('date')
                                        ->toArray();

        // Convert the merged collection back to an array
        // $mergedArray = $mergedCollection->toArray();
        $singleBillItem = [
            "bill_item_quantity" => null,
            "bill_item_rate" => null,
            "bill_item_total" => null,
            "bill_item_avg_rate" => null,
        ];
        $singleSaleItem = [
            "sale_item_quantity" => null,
            "sale_item_rate" => null,
            "sale_item_total" => null,
            "sale_item_avg_rate" => null,
        ];
        $singleClosingDate = [
            "closing_date_quantity" => null,
            "closing_date_rate" => null,
            "closing_date_total" => null,
            "closing_date_avg_rate" => null,
        ];
        $mergedArray = array_merge($billItemDates, $saleItemDates, $closingDates);
        $uniqueKeys = array_keys($mergedArray);
        // dump($uniqueKeys);
        sort($uniqueKeys);

        $mergedArray = [];
        foreach($uniqueKeys as $key){
            $mergedItem = null;
            if( isset($billItemDates[$key]) ){
                $mergedItem = $billItemDates[$key];
            } else {
                $mergedItem = $singleBillItem;
            }
            
            if( isset($saleItemDates[$key]) ){
                $mergedItem = array_merge($mergedItem, $saleItemDates[$key]);
            } else {
                $mergedItem = array_merge($mergedItem, $singleSaleItem);
            }

            if( isset($closingDates[$key]) ){
                $mergedItem = array_merge($mergedItem, $closingDates[$key]);
            } else {
                $mergedItem = array_merge($mergedItem, $singleClosingDate);
            }
            // dd($mergedItem);
            $mergedArray[] = $mergedItem;
        }


        $closingQuantity = 0;

        foreach ($mergedArray as &$item) {
            if (isset($item['bill_item_quantity'])) {
                $billQuantity = (float) $item['bill_item_quantity'];
                $item['closing_date_quantity'] = $closingQuantity + $billQuantity;
                $closingQuantity += $billQuantity;
            }
            if (isset($item['sale_item_quantity'])) {
                $saleQuantity = (float) $item['sale_item_quantity'];
                $item['closing_date_quantity'] = $closingQuantity - $saleQuantity;
                $closingQuantity -= $saleQuantity;
            }
            // if (isset($item['closing_date_quantity'])) {
            //     $closingQuantity = (float) $item['closing_date_quantity'];
            // }
        }
        // dd($mergedArray);
        // dump($billItemDates, $saleItemDates, $closingDates, $mergedArray);
        // dump($uniqueKeys);

        // return $uniqueDates;
        $data = [
            'mergedItems' => $mergedArray,
            'bill_item' => $bill_item,
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
