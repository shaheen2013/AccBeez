<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::find(1);
        $permissions = Permission::all()->pluck('name');
        $role->syncPermissions($permissions);

        $role = Role::find(2);

        $permission[] = Permission::where('name','like','%create')->pluck('name');
        $permission[] = Permission::where('name','like','%view')->pluck('name');
        $permissions = [];
        $permissions = array_merge($permissions,$permission[0]->toArray(),$permission[1]->toArray());

        $role->syncPermissions($permissions);
    }
}
