<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Company;
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
        $startDate = Carbon::now()->subDays(780);
        $bills = count(Bill::all());

        for ($i = $bills+1; $i <= $bills+120; $i++) {
            $date = $startDate->copy()->addDays(rand(0, 780))->format('Y-m-d');
            // $company_ids = Company::pluck('id')->toArray();

            DB::table('bills')->insert([
                'description' => 'Bill description ' . $i,
                'date' => $date,
                'invoice_total' => 0,
                'invoice_number' => mt_rand(10000, 99999).'-'.$i,
                'client_id' => rand(1, 3),
                'company_id' => rand(1, 5),
                'created_at' => $date . ' ' . $startDate->copy()->addDays(rand(0, 780))->format('H:i:s'),
                'updated_at' => $date . ' ' . $startDate->copy()->addDays(rand(0, 780))->format('H:i:s'),
            ]);
        }
    }
}
