<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::where('email','superadmin@gmail.com')->first();
        $user->syncRoles(['Super-Admin']);

        $user = User::find(2);
        $user->syncRoles(['Admin']);
    }
}
