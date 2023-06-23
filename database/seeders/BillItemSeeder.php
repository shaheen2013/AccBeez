<?php

namespace Database\Seeders;

use App\Models\Bill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BillItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // $billIds = DB::table('bills')->where('id', '>', 61)->pluck('id')->toArray();
        $billIds = DB::table('bills')->pluck('id')->toArray();
        $groceryItems = [
            'Apple',
            'Banana',
            'Orange',
            'Tomato',
            'Onion',
            'Carrot',
            'Lettuce',
            'Potato',
            'Cucumber',
            'Garlic',
            'Bread',
            'Milk',
            'Eggs',
            'Chicken',
            'Beef',
            'Rice',
            'Pasta',
            'Cheese',
            'Yogurt',
            'Butter',
            'Sugar',
            'Salt',
            'Pepper',
            'Coffee',
            'Tea',
            'Juice',
            'Cereal',
            'Oatmeal',
            'Ice Cream',
            'Chocolate',
        ];

        foreach ($billIds as $billId) {
            $numItems = rand(3, 7); // Randomly determine the number of items per bill

            $bill_total = Bill::find($billId)->invoice_total;
            // dd($bill_total);

            for ($i = 1; $i <= $numItems; $i++) {
                $item = $groceryItems[rand(0,29)];
                $rate = rand(10, 1000);
                $unit = 'pcs';
                $quantity = rand(1, 50);
                $total = $rate * $quantity;
                $bill_total += $total;

                $bill = DB::table('bills')->where('id', $billId)->first();
                $createdAt = $bill->created_at;
                $updatedAt = $bill->updated_at;

                DB::table('bill_items')->insert([
                    'bill_id' => $billId,
                    'Sku' => $item.'-'.rand(1,3),
                    'name' => $item,
                    'rate' => $rate,
                    'unit' => $unit,
                    'quantity' => $quantity,
                    'total' => $total,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                DB::table('bills')
                    ->where('id', $billId)
                    ->update(['invoice_total' => $bill_total]);
            }
        }

    }
}
