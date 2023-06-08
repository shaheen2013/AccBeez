<?php

namespace Database\Seeders;

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
            $numItems = rand(1, 4); // Randomly determine the number of items per bill

            $item = $groceryItems[rand(0,29)];
            $bill_total = 0;
            for ($i = 1; $i <= $numItems; $i++) {
                $rate = rand(10, 100);
                $unit = 'pcs';
                $quantity = rand(1, 10);
                $total = $rate * $quantity;
                $bill_total += $total;

                $bill = DB::table('bills')->where('id', $billId)->first();
                $createdAt = $bill->created_at;
                $updatedAt = $bill->updated_at;

                DB::table('bill_items')->insert([
                    'bill_id' => $billId,
                    'sku' => $item.'-'.rand(1,3),
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
