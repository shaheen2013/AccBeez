<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index()
    {
        $registers = DB::table('bill_items')
                        ->leftJoin('bills', 'bill_items.bill_id', '=', 'bills.id')
                        ->select('sku', DB::raw('SUM(quantity) as total_items'),
                                            DB::raw('SUM(total) as total_cost'),
                                            DB::raw('round(SUM(total) / SUM(quantity),2) as avg_cost'),
                                            DB::raw('MONTH(bills.date) as month')
                                    )
                        ->groupBy(['sku', 'month'])
                        ->orderBy('sku')
                        ->orderBy('month')
                        ->get();
        $distinctMonths = DB::table('bills')
                            ->select(DB::raw('MONTH(bills.date) as month'))
                            ->orderBy('month')
                            ->pluck('month', 'month')
                            ->unique()->toArray();


        $grouped = $registers->mapToGroups(function ($item) {
            return [$item->sku => [
                "sku" => $item->sku,
                "avg_cost" => $item->avg_cost,
                "month" => $item->month,
            ]];
        });

        $groupedWithNulls = $grouped->map(function ($items) use ($distinctMonths) {
            $groupedItemsByMonth = $items->keyBy('month');
            // dd($groupedItemsByMonth, $items);

            $filledItems = [];
            foreach ($distinctMonths as $month) {
                if ($groupedItemsByMonth->has($month)) {
                    $filledItems[] = $groupedItemsByMonth[$month];
                } else {
                    $filledItems[] = [
                        "total_items" => null,
                        "total_cost" => null,
                        "avg_cost" => null,
                        "month" => $month,
                    ];
                }
            }
            return $filledItems;
        });



        $simpleList = $grouped->map(function ($items) use ($distinctMonths) {
            $groupedItemsByMonth = $items->keyBy('month');
            // dd($groupedItemsByMonth, $items);

            foreach ($distinctMonths as $month) {
                if ($groupedItemsByMonth->has($month)) {
                    $outputItem['sku'] = $groupedItemsByMonth[$distinctMonths[$month]]['sku'];
                    $outputItem['month-'.$month] = $groupedItemsByMonth[$month]['avg_cost'];
                } else {
                    $outputItem['month-'.$month] = null;
                }
            }
            return $outputItem;
        });

        // dd($distinctMonths);
        // dd($distinctMonths, $registers, $grouped);
        // dd($distinctMonths, $grouped);
        // dd($groupedWithNulls);

        // dd($registers, $grouped);

        $data = [
            'distinct_months' => $distinctMonths,
            'registers' => $registers,
            'grouped' => $grouped,
            'groupedWithNulls' => $groupedWithNulls,
            'register_list' => array_values($simpleList->toArray()),
        ];
        return response()->json($data);
    }
}
