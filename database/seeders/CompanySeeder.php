<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'name'=>'Company 01'
            ],
            [
                'name'=>'Company 02'
            ],
            [
                'name'=>'Company 03'
            ],
            [
                'name'=>'Company 04'
            ],
            [
                'name'=>'Company 05'
            ],
        ]);
    }
}
