<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Bom;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        Bom::truncate();
        Sale::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->call([
            UserSeeder::class,
            BomSeeder::class,
            //SalesSeeder::class,
        ]);

        Sale::factory()->count(500)->create();

    }
}
