<?php

namespace App\Http\Controllers;

use App\Exports\BalanceSheetBladeExport;
use App\Exports\RegisterBladeExport;
use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;
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
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class RegisterController extends Controller
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


         $distinctMonths = DB::table('bills')
                            ->select(DB::raw("DATE_FORMAT(bills.date, '%Y-%m') as month"))
                            ->where('company_id', $company_id)
                            ->when(!empty($year), function (Builder $query) use ($year) {
                                return $query->whereRaw('YEAR(bills.date) = ?', [$year]);
                            })
                            ->orderBy('month')
                            ->pluck('month', 'month')
                            ->unique()
                            ->toArray();
                            
        $subquery = DB::table('sale_items')
        ->select('sku', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_cost'))
        ->groupBy('sku')
        ->toSql();
        $registers = DB::table('bill_items')
            ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
            ->leftJoin(DB::raw("($subquery) as sale_items"), function ($join) {
                $join->on('bill_items.sku', '=', 'sale_items.sku');
            })
            ->select(
                'bill_items.name',
                'bill_items.sku',
                'bill_items.id as bill_item_id',
                'bill_items.unit',
                DB::raw('SUM(bill_items.quantity) - IFNULL(sale_items.total_quantity, 0) as total_items'),
                DB::raw('SUM(bill_items.total) - IFNULL(sale_items.total_cost, 0) as total_cost'),
                DB::raw('SUM(bill_items.total) / SUM(bill_items.quantity) as avg_cost'),
                DB::raw('YEAR(bills.date) as year'),
                DB::raw("DATE_FORMAT(bills.date, '%Y-%m') as month")
            )
            ->when(!empty($keyword), function (Builder $query) use ($keyword) {
                return $query->where('sku', 'LIKE', '%' . $keyword . '%');
            })
            ->when(!empty($year), function (Builder $query) use ($year) {
                return $query->whereRaw('YEAR(bills.date) = ?', [$year]);
            })
            ->where('bills.company_id', $company_id)
            ->groupBy('sku', 'month', 'year')
            ->orderBy('sku')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        // $registers = $registerQuery->paginate($limit);
         $grouped = $registers->mapToGroups(function ($item) {
            return [$item->sku => [
                "bill_item_id" => $item->bill_item_id,
                "name" => $item->name,
                "sku" => $item->sku,
                'unit' => $item->unit,
                "total_items" => $item->total_items,
                "total_cost" => $item->total_cost,
                "avg_cost" => $item->avg_cost,
                "month" => $item->month,
                "year" => $item->year,
            ]];
        });
        // dd($registers->toArray());

         $simpleList = $grouped->map(function ($items) use ($distinctMonths) {
            $groupedItemsByMonth = $items->keyBy('month');
            // error_log(json_encode($groupedItemsByMonth));
            $outputItem['name'] = $items[0]['name'];
            $outputItem['sku'] = $items[0]['sku'];
            $outputItem['bill_item_id'] = $items[0]['bill_item_id'];

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

    public function exportData(Request $request){
        $year = $request->has('year') ? $request->year : Carbon::now()->format('Y');
        $company_id = getCompanyIdBySlug($request->slug);

       $distinctMonths = DB::table('bills')
           ->select(DB::raw("DATE_FORMAT(bills.date, '%Y-%m') as month"))
           ->where('company_id', $company_id)
           ->when(!empty($year), function (Builder $query) use ($year) {
               return $query->whereRaw('YEAR(bills.date) = ?', [$year]);
           })
           ->orderBy('month')
           ->pluck('month', 'month')
           ->unique()
           ->toArray();

       $registers = DB::table('bill_items')
           ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
           ->select('name', 'sku', 'bill_items.id as bill_item_id', DB::raw('SUM(quantity) as total_items'),
               DB::raw('SUM(total) as total_cost'),
               DB::raw('round(SUM(total) / SUM(quantity),2) as avg_cost'),
               DB::raw('YEAR(bills.date) as year'),
               // DB::raw('MONTH(bills.date) as month')
               DB::raw("DATE_FORMAT(bills.date, '%Y-%m') as month")
           )
           ->where('bills.company_id', $company_id)
           ->when(!empty($year), function (Builder $query) use ($year) {
               return $query->whereRaw('YEAR(bills.date) = ?', [$year]);
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
               "name" => $item->name,
               "sku" => $item->sku,
               "total_items" => $item->total_items,
               "total_cost" => $item->total_cost,
               "avg_cost" => $item->avg_cost,
               "month" => $item->month,
               "year" => $item->year,
           ]];
       });



       $simpleList = $grouped->map(function ($items) use ($distinctMonths) {
           $groupedItemsByMonth = $items->keyBy('month');
           $outputItem['name'] = $items[0]['name'];
           $outputItem['sku'] = $items[0]['sku'];
           $outputItem['bill_item_id'] = $items[0]['bill_item_id'];

           foreach ($distinctMonths as $month) {
               if ($groupedItemsByMonth->has($month)) {
                   $outputItem['month-'.$month] = $groupedItemsByMonth[$month]['total_items'] . '|' . $groupedItemsByMonth[$month]['total_cost'];
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
           //'register_list' => array_values(array_splice($simpleList, $startAt, $perPage)),
            'register_list' => array_values($simpleList),
       ];

         if($request->fileType === 'pdf'){
             $pdf = Pdf::loadView('register.export-data', $data);
             return $pdf->download('invoice.pdf');
         }else{
             $exporter = new RegisterBladeExport($data);
             $file_name = 'Register_'.date('U').'.'.$request->fileType;
             return Excel::download($exporter, $file_name);
         }

       //return view('', $data);
   }


    public function close(Request $request)
    {
        $input = $request->all();
        $input['date'] = Carbon::now()->format('Y-m-d');
        // $input['date'] = '2023-02-04';
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




    public function view(Request $request, $id)
    {
        // dd($request->all(), $id);
        $year = $request->year;
        $bill_item = BillItem::with('closingDates')->find($id);
        $closingDates = $bill_item->closingDates;
        $sku = $bill_item->sku;
        $company_id = getCompanyIdBySlug($request->slug);
        // Find unique dates in BillItem
        $billItemDates = Bill::leftJoin('bill_items', 'bills.id', '=', 'bill_items.bill_id')
                                ->groupBy('date')
                                ->select('bills.date', 'bill_items.sku', DB::raw("'billItem' as model"), 'bill_items.unit',
                                        DB::raw('SUM(quantity) as bill_item_quantity'),
                                        DB::raw('SUM(total) as bill_item_total'),
                                        DB::raw('SUM(total) / SUM(quantity) as bill_item_rate'),
                                        DB::raw('0 as bill_item_avg_rate'),
                                        DB::raw("GROUP_CONCAT(bills.invoice_number SEPARATOR ',') as `invoices`")
                                )
                                ->where('bill_items.company_id', $company_id)
                                ->where('sku', $sku)
                                ->when(!empty($year), function ($query) use ($year) {
                                    return $query->whereRaw('YEAR(bills.date) = ?', [$year]);
                                })
                                ->distinct('date')
                                ->get()
                                ->keyBy('date')
                                ->toArray();
        $saleItemDates = Sale::leftJoin('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                                ->groupBy('date')
                                ->select('sales.date', 'sale_items.sku', DB::raw("'saleItem' as model"), 'sale_items.unit',
                                        DB::raw('SUM(quantity) as sale_item_quantity'),
                                        DB::raw('SUM(total) as sale_item_total'),
                                        DB::raw('SUM(total) / SUM(quantity) as sale_item_rate'),
                                        DB::raw('0 as sale_item_avg_rate'),
                                )
                                ->where('sku', $sku)
                                ->when(!empty($year), function ($query) use ($year) {
                                    return $query->whereRaw('YEAR(sales.date) = ?', [$year]);
                                })
                                ->distinct('date')
                                ->get()
                                ->keyBy('date')
                                ->toArray();
        $closingDates = ClosingDate::select('date', 'sku', DB::raw("'closingDate' as model"),
                                                DB::raw('0 as closing_date_avg_rate')
                                        )
                                        ->where('sku', $sku)
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
        $bill_item_avg_rate = 0;
        $bill_item_sum_quantity = 0;
        $bill_item_sum_total = 0;
        $sale_item_avg_rate = 0;
        $sale_item_sum_quantity = 0;
        $sale_item_sum_total = 0;
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

    public function exportBalanceSheet(Request $request){
        $year = 2023;
        $bill_item = BillItem::with('closingDates')->find($request->id);
        $closingDates = $bill_item->closingDates;
        $sku = $bill_item->sku;

        // Find unique dates in BillItem
        $billItemDates = Bill::leftJoin('bill_items', 'bills.id', '=', 'bill_items.bill_id')
            ->groupBy('date')
            ->select('bills.date', 'bill_items.sku', DB::raw("'billItem' as model"),
                DB::raw('SUM(quantity) as bill_item_quantity'),
                DB::raw('SUM(total) as bill_item_total'),
                DB::raw('SUM(total) / SUM(quantity) as bill_item_rate'),
                DB::raw('0 as bill_item_avg_rate'),
                DB::raw("GROUP_CONCAT(bills.invoice_number SEPARATOR ',') as `invoices`")
            )
            ->where('sku', $sku)
            ->when(!empty($year), function ($query) use ($year) {
                return $query->whereRaw('YEAR(bills.date) = ?', [$year]);
            })
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
            ->when(!empty($year), function ($query) use ($year) {
                return $query->whereRaw('YEAR(sales.date) = ?', [$year]);
            })
            ->distinct('date')
            ->get()
            ->keyBy('date')
            ->toArray();
        $closingDates = ClosingDate::select('date', 'sku', DB::raw("'closingDate' as model"),
            DB::raw('0 as closing_date_avg_rate')
        )
            ->where('sku', $sku)
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
        $bill_item_avg_rate = 0;
        $bill_item_sum_quantity = 0;
        $bill_item_sum_total = 0;
        $sale_item_avg_rate = 0;
        $sale_item_sum_quantity = 0;
        $sale_item_sum_total = 0;
        // dump($mergedArray);


        foreach ($mergedArray as &$item) {
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
                $closingTotal = $closingQuantity + $billTotal - $saleTotal;
                $closingQuantity = $closingQuantity + $billQuantity - $saleQuantity;
                $closingRate = $closingTotal / $closingQuantity;
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
            }
            elseif (isset($item['sale_item_quantity'])) {
                // $item['sale_item_rate'] = $closingRate;
                $saleQuantity = (float) $item['sale_item_quantity'];
                $saleTotal = (float) $item['sale_item_total'];
                $closingQuantity = $closingQuantity - $saleQuantity;
                $closingTotal = $closingTotal - $saleTotal;
                $closingRate = $closingTotal / $closingQuantity;
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

        if($request->fileType === 'pdf'){
            $pdf = Pdf::loadView('register.balance-sheet', $data)->setPaper('a4', 'landscape');
            return $pdf->download('balance-sheet.pdf');
        }else{
            $exporter = new BalanceSheetBladeExport($data);
            $file_name = 'Register_'.date('U').'.'.$request->fileType;
            return Excel::download($exporter, $file_name);
        }

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
