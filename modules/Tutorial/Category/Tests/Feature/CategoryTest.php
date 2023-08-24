<?php

namespace Tutorial\Category\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Illuminate\Support\Str;
use Tutorial\Category\Models\Category;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\RolePermissions\Models\Permission;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     */

    public function test_permitted_user_can_see_categories_panel(): void
    {
        $this->actionAsAdmin();
        $this->get(route('categories.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_categories_panel(): void
    {
        $this->actionAsUser();
        $this->get(route('categories.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_create_category(): void
    {
        $this->actionAsAdmin();
        $this->createCategory();
        $this->assertCount(1, Category::all());
    }

    public function test_permitted_user_can_update_category(): void
    {
        $this->actionAsAdmin();
        $newTitle = 'title123';
        $this->createCategory();
        $this->assertCount(1, Category::all());

        $this->patch(route('categories.update', 1), ['title' => $newTitle, 'slug' => fake()->slug()]);
        $this->assertCount(1, Category::whereTitle($newTitle)->get());
    }

    public function test_permitted_user_can_delete_category(): void
    {
        $this->actionAsAdmin();
        $this->createCategory();
        $this->assertCount(1, Category::all());

        $this->delete(route('categories.destroy', 1))->assertOk();
    }

    private function actionAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
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

    private function createCategory()
    {
        $this->post(route('categories.store'), ['title' => fake()->title, 'slug' => fake()->slug()]);
    }
}
