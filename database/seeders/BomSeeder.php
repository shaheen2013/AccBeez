<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('boms')->insert([
            [
                'name' => 'Bom 01',
                'invoice_total' => 10
            ],
            [
                'name' => 'Bom 02',
                'invoice_total' => 30
            ],
            [
                'name' => 'Bom 03',
                'invoice_total' => 60
            ],
            [
                'name' => 'Bom 04',
                'invoice_total' => 108
            ],
        ]);
    }
}
