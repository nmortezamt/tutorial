<?php

namespace Tutorial\RolePermissions\Database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\RolePermissions\Models\Role;
use Tutorial\User\Models\User;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(Permission::$permissions as $permission){

            Permission::findOrCreate($permission);
        }
        foreach(Role::$roles as $name => $permission){

            Role::findOrCreate($name)->syncPermissions($permission);
        }
    }
}
