<?php

namespace Tutorial\User\Database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\RolePermissions\Models\Role;
use Tutorial\User\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(User::$defaultUser as $user)
        {
            User::firstOrCreate(
                ['email'=>$user['email']],

                ['name'=>$user['name'],
                'email'=>$user['email'],
                'password'=>$user['password'],
                ]
            )->assignRole($user['role'])->markEmailAsVerified();
        }
    }
}
