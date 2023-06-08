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
        $startDate = Carbon::now()->subDays(120);

        for ($i = 1; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays(rand(0, 120))->format('Y-m-d');
            $description = 'Bill description ' . $i;
            // $invoiceTotal = rand(100, 1000);
            // $invoiceNumber = 'INV-' . rand(1000, 9999);
            // dd($date, $startDate, $startDate->copy(),$startDate->copy()->addDays(rand(0, 30))->format('H:i:s'));

            DB::table('bills')->insert([
                'description' => 'Bill description ' . $i,
                'date' => $date,
                'invoice_total' => rand(1000, 100000),
                'invoice_number' => 'INV# ' . rand(1000, 9999),
                'client_id' => rand(1, 3),
                'created_at' => $date . ' ' . $startDate->copy()->addDays(rand(0, 120))->format('H:i:s'),
                'updated_at' => $date . ' ' . $startDate->copy()->addDays(rand(0, 120))->format('H:i:s'),
            ]);
        }
    }
}
