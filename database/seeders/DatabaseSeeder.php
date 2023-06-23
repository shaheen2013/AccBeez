<?php

namespace Database\Seeders;

use App\Models\Bom;
use App\Models\BomItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class,
            PermissionToRoleSeeder::class,
            AssignRoleSeeder::class,
            BillSeeder::class,
            BillItemSeeder::class,
        ]);

        Bom::factory(100)
            ->has(BomItem::factory()->count(10))
            ->create();

        Sale::factory(100)
            ->has(SaleItem::factory()->count(10))
            ->create();


    }
}
