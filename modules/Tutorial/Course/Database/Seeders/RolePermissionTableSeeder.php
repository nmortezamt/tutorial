<?php

namespace Tutorial\Course\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tutorial\User\Models\User;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::findOrCreate('manage_categories');
        Permission::findOrCreate('manage_role_permission');
        Permission::findOrCreate('teach');

        // Role::findOrCreate('teacher')->syncPermissions('teach');
        // $user =User::find(1)->givepermissionTo('teach');

    }
}
