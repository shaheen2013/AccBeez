<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BomSale;
use App\Models\BomSaleItem;
use App\Models\ClosingDate;
use App\Models\ProductionSale;
use App\Models\Sale;
use DateTime;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FcRegisterController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 10);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $year = Arr::get($searchParams, 'year', '');
        $perPage = $request->input('limit') ?? 10;
        $page = $request->input('page') ?? 1;
        $startAt = ($perPage * ($page-1));
        $company_id = getCompanyIdBySlug($request->slug);


         $distinctMonths = DB::table('bom_sales')
                            ->select(DB::raw("DATE_FORMAT(bom_sales.date, '%Y-%m') as month"))
                            ->where('company_id', $company_id)
                            ->when(!empty($year), function (Builder $query) use ($year) {
                                return $query->whereRaw('YEAR(bom_sales.date) = ?', [$year]);
                            })
                            ->orderBy('month')
                            ->pluck('month', 'month')
                            ->unique()
                            ->toArray();
        $subquery = DB::table('production_sale_items')
                        ->select('name',
                                DB::raw('SUM(quantity) as total_quantity'),
                                DB::raw('SUM(total) as total_cost'))
                        ->groupBy('name')
                        ->toSql();                    
        $registers = DB::table('bom_sale_items')
            ->leftJoin('bom_sales', 'bom_sale_items.bom_sale_id', '=', 'bom_sales.id')
            ->leftJoin(DB::raw("($subquery) as sale_items"), function($join){
                $join->on('bom_sale_items.name', '=', 'sale_items.name');
            })
            ->select(
                'bom_sale_items.name',
                'bom_sale_items.id as bom_sale_item_id',
                'bom_sale_items.unit',
                DB::raw('SUM(bom_sale_items.quantity) - IFNULL(sale_items.total_quantity, 0) as total_items'),
                DB::raw('SUM(bom_sale_items.total) - IFNULL(sale_items.total_cost, 0) as total_cost'),
                DB::raw('SUM(bom_sale_items.total) / SUM(bom_sale_items.quantity) as avg_cost'),
                DB::raw('YEAR(bom_sales.date) as year'),
                DB::raw("DATE_FORMAT(bom_sales.date, '%Y-%m') as month")
            )
            ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                return $query->where('sku', 'LIKE', '%' . $keyword . '%');
            })
            ->when(!empty($year), function (Builder $query) use ($year) {
                return $query->whereRaw('YEAR(bom_sales.date) = ?', [$year]);
            })
            ->where('bom_sales.company_id', $company_id)
            ->groupBy('name', 'month', 'year')
            ->orderBy('name')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        // $registers = $registerQuery->paginate($limit);
         $grouped = $registers->mapToGroups(function ($item) {
            return [$item->name => [
                "bom_sale_item_id" => $item->bom_sale_item_id,
                "name" => $item->name,
                'unit' => $item->unit,
                "total_items" => $item->total_items,
                "total_cost" => $item->total_cost,
                "avg_cost" => $item->avg_cost,
                "month" => $item->month,
                "year" => $item->year,
            ]];
        });

         $simpleList = $grouped->map(function ($items) use ($distinctMonths) {
            $groupedItemsByMonth = $items->keyBy('month');
            // error_log(json_encode($groupedItemsByMonth));
            $outputItem['name'] = $items[0]['name'];
            $outputItem['bom_sale_item_id'] = $items[0]['bom_sale_item_id'];

            foreach ($distinctMonths as $month) {
                if ($groupedItemsByMonth->has($month)) {
                    $outputItem['month-'.$month] = $groupedItemsByMonth[$month]['avg_cost'];
                    $outputItem['month-'.$month] = [
                        "total_items" => $groupedItemsByMonth[$month]['total_items'],
                        "total_cost" => $groupedItemsByMonth[$month]['total_cost'],
                        "unit" => $groupedItemsByMonth[$month]['unit']
                    ];
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
            // 'grouped' => $grouped,
            'total' => count($simpleList),
            'register_list' => array_values(array_splice($simpleList, $startAt, $perPage)),
            // 'register_list' => array_values($simpleList),
        ];

        return response()->json($data);

    }

    public function view(Request $request, $id)
    {
        // dd($request->all(), $id);
        $year = $request->year;
        $bill_item = BomSaleItem::with('closingDates')->find($id);
        $closingDates = $bill_item->closingDates;
        $name = $bill_item->name;
        $company_id = getCompanyIdBySlug($request->slug);
        // Find unique dates in BillItem
        $billItemDates = BomSale::leftJoin('bom_sale_items', 'bom_sales.id', '=', 'bom_sale_items.bom_sale_id')
                                ->groupBy('date')
                                ->select('bom_sales.date', 'bom_sale_items.name', DB::raw("'billItem' as model"), 'bom_sale_items.unit',
                                        DB::raw('SUM(quantity) as bill_item_quantity'),
                                        DB::raw('SUM(total) as bill_item_total'),
                                        DB::raw('SUM(total) / SUM(quantity) as bill_item_rate'),
                                        DB::raw('0 as bill_item_avg_rate'),
                                        DB::raw("GROUP_CONCAT(bom_sales.invoice_number SEPARATOR ',') as `invoices`")
                                )
                                ->where('bom_sale_items.company_id', $company_id)
                                ->where('name', $name)
                                ->when(!empty($year), function ($query) use ($year) {
                                    return $query->whereRaw('YEAR(bom_sales.date) = ?', [$year]);
                                })
                                ->distinct('date')
                                ->get()
                                ->keyBy('date')
                                ->toArray();
        $saleItemDates = ProductionSale::leftJoin('production_sale_items', 'production_sales.id', '=', 'production_sale_items.production_sale_id')
                                ->groupBy('date')
                                ->select('production_sales.date', 'production_sale_items.sku', DB::raw("'saleItem' as model"), 'production_sale_items.unit',
                                        DB::raw('SUM(quantity) as sale_item_quantity'),
                                        DB::raw('SUM(total) as sale_item_total'),
                                        DB::raw('SUM(total) / SUM(quantity) as sale_item_rate'),
                                        DB::raw('0 as sale_item_avg_rate'),
                                )
                                ->where('production_sale_items.company_id', $company_id)
                                ->where('name', $name)
                                ->when(!empty($year), function ($query) use ($year) {
                                    return $query->whereRaw('YEAR(production_sales.date) = ?', [$year]);
                                })
                                ->distinct('date')
                                ->get()
                                ->keyBy('date')
                                ->toArray();
        $closingDates = ClosingDate::select('date', 'sku', DB::raw("'closingDate' as model"),
                                                DB::raw('0 as closing_date_avg_rate')
                                        )
                                        ->when(!empty($year), function ($query) use ($year) {
                                            return $query->whereRaw('YEAR(date) = ?', [$year]);
                                        })
                                        ->distinct('date')
                                        ->get()
                                        ->keyBy('date')
                                        ->toArray();

        // Convert the merged collection back to an array
        // $mergedArray = $mergedCollection->toArray();
        $mergedArray = array_merge($billItemDates, $saleItemDates, $closingDates);
        $uniqueDates = array_keys($mergedArray);
        sort($uniqueDates);
        // dump($uniqueDates, $mergedArray);
        if(!$uniqueDates){
            $data = [
                'mergedItems' => $mergedArray,
                'bill_item' => $bill_item,
                'uniqueDates' => $uniqueDates
            ];
            return $data;
        }

        $startDate = min($uniqueDates);
        $endDate = max($uniqueDates);
        $currentDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        // dump($currentDate, $endDate);
        if($currentDate == $endDate){
            $yearMonth = $currentDate->format('Y-m');
            $yearMonths[$yearMonth] = $yearMonth.'-01';
            $currentDate->modify('+1 month');
        } else {
            while ($currentDate < $endDate) {
                $yearMonth = $currentDate->format('Y-m');
                $yearMonths[$yearMonth] = $yearMonth.'-01';
                $currentDate->modify('+1 month');
            }
        }
        $yearMonths = array_values($yearMonths);
        $uniqueDates = array_unique(array_merge($uniqueDates, $yearMonths));
        sort($uniqueDates);
        // dd($yearMonths, $uniqueDates);

        $singleBillItem = [
            "bill_item_quantity" => null,
            "bill_item_rate" => null,
            "bill_item_total" => null,
            "bill_item_avg_rate" => null,
            "invoices" => null,
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
        $singleOpeningDate = [
            "opening_date_quantity" => null,
            "opening_date_rate" => null,
            "opening_date_total" => null,
            "opening_date_avg_rate" => null,
            "opening_date" => false,
        ];
        $mergedArray = [];
        foreach($uniqueDates as $key){
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

            $mergedItem = array_merge($mergedItem, $singleOpeningDate);
            $date = DateTime::createFromFormat("Y-m-d", $key);
            $day = $date->format("d");
            if($day == '01'){
                $mergedItem['date'] = $date->format("Y-m-d");
                $mergedItem['opening_date'] = true;
            }
            // dd($mergedItem);
            $mergedArray[] = $mergedItem;
        }


        $closingRate = 0;
        $closingQuantity = 0;
        $closingTotal = 0;
        // dump($mergedArray);


        foreach ($mergedArray as $index => &$item) {
            if( $item['opening_date'] ){
                // dump($item['opening_date']);
                $item['opening_date_rate'] = $closingRate;
                $item['opening_date_quantity'] = $closingQuantity;
                $item['opening_date_total'] = $closingTotal;
            }

            if ( isset($item['bill_item_quantity']) && isset($item['sale_item_quantity']) ) {
                // $item['sale_item_rate'] = $closingRate;
                $billQuantity = (float) $item['bill_item_quantity'];
                $saleQuantity = (float) $item['sale_item_quantity'];
                $billTotal = (float) $item['bill_item_total'];
                $saleTotal = (float) $item['sale_item_total'];
                
                $closingTotal = $closingTotal + $billTotal - $saleTotal;
                $closingQuantity = $closingQuantity + $billQuantity - $saleQuantity;
                
                if($closingQuantity == 0){
                    $closingRate = 0;
                }else{
                    $closingRate = $closingTotal / $closingQuantity;
                }
                
                $item['closing_date_rate'] = $closingRate;
                $item['closing_date_quantity'] = $closingQuantity;
                $item['closing_date_total'] = $closingTotal;

                
            }
            elseif ( isset($item['bill_item_quantity']) ) {
                $billQuantity = (float) $item['bill_item_quantity'];
                $billTotal = (float) $item['bill_item_total'];
                $closingQuantity = $closingQuantity + $billQuantity;
                $closingTotal = $closingTotal + $billTotal;
                $closingRate = $closingTotal / $closingQuantity;
                $item['closing_date_rate'] = $closingRate;
                $item['closing_date_quantity'] = $closingQuantity;
                $item['closing_date_total'] = $closingTotal;
                // dump($item);
                if($item['opening_date'] == true){
                    $item['opening_date_quantity'] = $closingQuantity;
                    $item['opening_date_total'] = $closingTotal;
                    $item['opening_date_rate'] = $closingRate;
                    $item['bill_item_quantity'] = null;
                    $item['bill_item_total'] = null;
                    $item['bill_item_rate'] = null;
                }
            }
            elseif (isset($item['sale_item_quantity'])) {
                // $item['sale_item_rate'] = $closingRate;
                $saleQuantity = (float) $item['sale_item_quantity'];
                $saleTotal = (float) $item['sale_item_total'];
                $closingQuantity = $closingQuantity - $saleQuantity;
                $closingTotal = $closingTotal - $saleTotal;
                if($closingQuantity == 0) {
                    $closingRate = 0;
                }else{
                    $closingRate = $closingTotal / $closingQuantity;
                }
                $item['closing_date_rate'] = $closingRate;
                $item['closing_date_quantity'] = $closingQuantity;
                $item['closing_date_total'] = $closingTotal;
            } else {
                // dd('$item', $item);
                $item['closing_date_rate'] = $closingRate;
                $item['closing_date_quantity'] = $closingQuantity;
                $item['closing_date_total'] = $closingTotal;
            }
        }
        // dd($mergedArray);
        // dump($billItemDates, $saleItemDates, $closingDates, $mergedArray);
        // dump($uniqueDates);
        $data = [
            'mergedItems' => $mergedArray,
            'bill_item' => $bill_item,
            'uniqueDates' => $uniqueDates
        ];
        return $data;
    }
}
