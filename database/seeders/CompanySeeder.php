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
                'name'=>'Company 01',
                'slug' => 'company-01'
            ],
            [
                'name'=>'Company 02',
                'slug' => 'company-02'
            ],
            [
                'name'=>'Company 03',
                'slug' => 'company-03'
            ],
            [
                'name'=>'Company 04',
                'slug' => 'company-04'
            ],
            [
                'name'=>'Company 05',
                'slug' => 'company-05'
            ],
        ]);
    }
}
