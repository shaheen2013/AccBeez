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
                'name'=>'HDFC Bank',
                'slug' => 'hdfc-bank'
            ],
            [
                'name'=>'BHP Group',
                'slug' => 'bhp-group'
            ],
            [
                'name'=>'CSL',
                'slug' => 'csl'
            ],
            [
                'name'=>'Woolworth Group',
                'slug' => 'woolworth-group'
            ],
            [
                'name'=>'Telstra',
                'slug' => 'telstra'
            ],
        ]);
    }
}
