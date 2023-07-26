<?php

namespace App\Http\Controllers;

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
                            
        $registers = DB::table('bom_sale_items')
            ->leftJoin('bom_sales', 'bom_sale_items.bom_sale_id', '=', 'bom_sales.id')
            ->select(
                'bom_sale_items.name',
                'bom_sale_items.id as bom_sale_item_id',
                'bom_sale_items.unit',
                DB::raw('SUM(bom_sale_items.quantity) as total_items'),
                DB::raw('SUM(bom_sale_items.total) as total_cost'),
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
}
