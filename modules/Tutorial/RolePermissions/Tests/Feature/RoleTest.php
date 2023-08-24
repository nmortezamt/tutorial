<?php

namespace Tutorial\RolePermissions\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Illuminate\Support\Str;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\RolePermissions\Models\Role;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    // user can see index rolePermission form
    public function test_permitted_user_can_see_index(): void
    {
        $this->actionAsAdmin();
        $this->get(route('role-permissions.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_index(): void
    {
        $this->actionAsUser();
        $this->get(route('role-permissions.index'))->assertStatus(403);
    }

    // user can create role

    public function test_permitted_user_can_create_role(): void
    {
        $this->actionAsAdmin();
        $this->post(route('role-permissions.store'),[
            'name'=>'test-role',
            'permissions'=>[Permission::PERMISSION_MANAGE_CATEGORIES,Permission::PERMISSION_MANAGE_TEACH]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals(count(Role::$roles)+1,Role::count());
    }

    public function test_normal_user_can_not_create_role(): void
    {
        $this->actionAsUser();
        $this->post(route('role-permissions.store'),[
            'name'=>'test-role-test',
            'permissions'=>[Permission::PERMISSION_MANAGE_CATEGORIES,Permission::PERMISSION_MANAGE_TEACH]
        ])->assertStatus(403);
        $this->assertEquals(count(Role::$roles),Role::count());
    }

    // user can see role edit form

    public function test_permitted_user_can_see_edit_role_form(): void
    {
        $this->actionAsAdmin();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit',$role->id))->assertOk();
    }

    public function test_normal_user_can_not_see_edit_role_form(): void
    {
        $this->actionAsUser();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit',$role->id))->assertStatus(403);
    }

    // user can update role

    public function test_permitted_user_can_update_role(): void
    {
        $this->actionAsAdmin();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update',$role->id),[
            'name'=>'new_role',
            'permissions'=>[Permission::PERMISSION_MANAGE_CATEGORIES]
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals('new_role',$role->fresh()->name);
    }

    public function test_normal_user_can_not_update_role(): void
    {
        $this->actionAsUser();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update',$role->id),[
            'name'=>'new_role',
            'permissions'=>[Permission::PERMISSION_MANAGE_CATEGORIES]
        ])->assertStatus(403);
        $this->assertEquals($role->name,$role->fresh()->name);
    }

    //user can delete role

    public function test_permitted_user_can_delete_role(): void
    {
        $this->actionAsAdmin();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy',$role->id))->assertOk();
        $this->assertEquals(count(Role::$roles),Role::count());
    }

    public function test_normal_user_can_not_delete_role(): void
    {
        $this->actionAsUser();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy',$role->id))->assertStatus(403);
        $this->assertEquals(count(Role::$roles)+1,Role::count());
    }

    private function actionAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }

    private function actionAsUser()
    {
        $this->createUser();
    }

    private function createUser()
    {
        $this->actingAs(User::factory()->make());
        auth()->user()->markEmailAsVerified();
        $this->seed(RolePermissionTableSeeder::class);
    }

    private function createRole()
    {
        return Role::create(['name'=>'test_role'])->syncPermissions([Permission::PERMISSION_MANAGE_CATEGORIES,Permission::PERMISSION_MANAGE_COURSE]);
    }
}
