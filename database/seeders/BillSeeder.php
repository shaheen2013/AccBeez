<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 bills
        DB::table('bills')->insert([
            'description' => Str::random(10),
            'date' => Carbon::create('2000', '01', '01'),
            'invoice_total' => rand(10, 100000)
        ]);
        $bills = Bill::all();

        // foreach ($bills as $bill) {
        //     // Create 3 bill items for each bill
        //     DB::table('bill_items')->insert([
        //         'description' => Str::random(10),
        //         'date' => Carbon::create('2000', '01', '01'),
        //         'invoice_total' => rand(10, 100000)
        //     ]);
        //     $billItems = Factory::of(BillItem::class)->times(3)->make([
        //         'bill_id' => $bill->id,
        //     ]);

        //     // Save bill items to the database
        //     $bill->billItems()->saveMany($billItems);
        // }
    }
}
