<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $operations = ['create','update','delete','view'];
        $modules = ['admin','user','bill','sale','inventory','purchase'];

        $permissions = [];

        foreach($modules as $module){
            foreach($operations as $operation){
                $permission = $module.'-'.$operation;
                $permission = Permission::create(['name'=>$permission,'guard_name'=>'web']);
                $permissions[] = $permission->toArray();
            }
        }

        // print_r($permissions);
        $roles = [];
        Role::create(['name'=>'Super-Admin','guard_name'=>'web']);
        Role::create(['name'=>'Admin','guard_name'=>'web']);

        // dd($roles,$permissions);

    }
}
