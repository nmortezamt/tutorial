<?php

namespace Tutorial\Category\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Illuminate\Support\Str;
use Tutorial\Category\Models\Category;
use Tutorial\Course\Database\Seeders\RolePermissionTableSeeder;

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
        $this->seed(RolePermissionTableSeeder::class);
        auth()->user()->givePermissionTo('manage_categories');
        $this->get(route('categories.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_categories_panel(): void
    {
        $this->actionAsAdmin();
        $this->get(route('categories.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_create_category(): void
    {
        $this->actionAsAdmin();
        $this->seed(RolePermissionTableSeeder::class);
        auth()->user()->givePermissionTo('manage_categories');
        $this->createCategory();
        $this->assertCount(1, Category::all());
    }

    public function test_permitted_user_can_update_category(): void
    {
        $this->actionAsAdmin();
        $this->seed(RolePermissionTableSeeder::class);
        auth()->user()->givePermissionTo('manage_categories');
        $newTitle = 'title123';
        $this->createCategory();
        $this->assertCount(1, Category::all());

        $this->patch(route('categories.update', 1), ['title' => $newTitle, 'slug' => fake()->slug()]);
        $this->assertCount(1, Category::whereTitle($newTitle)->get());
    }

    public function test_permitted_user_can_delete_category(): void
    {
        $this->actionAsAdmin();
        $this->seed(RolePermissionTableSeeder::class);
        auth()->user()->givePermissionTo('manage_categories');
        $this->createCategory();
        $this->assertCount(1, Category::all());

        $this->delete(route('categories.destroy', 1))->assertOk();
    }

    private function actionAsAdmin()
    {
        $user = User::create(
            [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        );

        $this->actingAs($user);
        auth()->user()->markEmailAsVerified();
    }

    private function createCategory()
    {
        $this->post(route('categories.store'), ['title' => fake()->title, 'slug' => fake()->slug()]);
    }
}
