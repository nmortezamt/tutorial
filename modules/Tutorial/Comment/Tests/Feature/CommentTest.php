<?php

namespace Tutorial\Comment\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tutorial\Category\Models\Category;
use Tutorial\Comment\Models\Comment;
use Tutorial\Course\Models\Course;
use Tutorial\User\Models\User;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\RolePermissions\Models\Permission;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_permitted_user_can_see_comments_index()
    {
        $this->actionAsAdmin();
        $this->get(route('comments.index'))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $this->get(route('comments.index'))->assertOk();

        $this->actionAsSuperAdmin();
        $this->get(route('courses.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_comments_index(): void
    {
        $this->actionAsUser();
        $this->get(route('comments.index'))->assertStatus(403);
    }

    public function test_user_can_create_comment()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->post(route('comments.store'), [
            'body' => 'first my comment',
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course)
        ]);
        $this->assertEquals(1, Comment::count());
    }

    public function test_user_can_reply_to_approved_comment()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        
        $this->post(route('comments.store'), [
            'body' => 'first my comment reply',
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course)
        ]);

        $this->post(route('comments.store'), [
            'body' => 'first my comment reply',
            'comment_id' => 1,
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course)
        ]);
        $this->assertEquals(2, Comment::count());
    }

    public function test_user_can_not_reply_to_unapproved_comment()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $comment = $this->createComment();
        $this->post(route('comments.store'), [
            'body' => 'first my comment reply',
            'comment_id' => $comment->id,
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course)
        ]);
        $this->assertEquals(1, Comment::count());
    }

    public function test_permitted_user_can_show_comment()
    {
        $this->actionAsAdmin();
        $comment = $this->createComment();
        $this->get(route('comments.show', $comment->id))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $comment = $this->createComment();
        $this->get(route('comments.show', $comment->id))->assertOk();
    }

    public function test_user_can_not_show_own_comment()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $comment = $this->createComment();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $this->get(route('comments.show', $comment->id))->assertStatus(403);
    }


    public function test_permitted_user_can_delete_comment()
    {
        $this->actionAsAdmin();
        $comment = $this->createComment();
        $this->delete(route('comments.destroy', $comment->id))->assertOk();
        $this->assertEquals(0, Comment::count());
    }

    public function test_user_can_not_delete_comment()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $comment = $this->createComment();
        $this->delete(route('comments.destroy', $comment->id))->assertStatus(403);
        $this->assertEquals(1, Comment::count());
    }

    public function test_permitted_user_can_change_status_comment_to_approve()
    {
        $this->actionAsAdmin();
        $comment = $this->createComment();
        $this->patch(route('comments.approve', $comment->id))->assertOk();
        $comment = $comment->fresh();
        $this->assertEquals(Comment::STATUS_APPROVED, $comment->status);
    }

    public function test_user_can_not_change_status_course_to_approve()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $comment = $this->createComment();
        $this->patch(route('comments.approve', $comment->id))->assertStatus(403);
        $comment = $comment->fresh();
        $this->assertEquals(Comment::STATUS_NEW, $comment->status);
    }

    public function test_permitted_user_can_change_status_comment_to_reject()
    {
        $this->actionAsAdmin();
        $comment = $this->createComment();
        $this->patch(route('comments.reject', $comment->id))->assertOk();
        $comment = $comment->fresh();
        $this->assertEquals(Comment::STATUS_REJECTED, $comment->status);
    }

    public function test_user_can_not_change_status_comment_to_reject()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $comment = $this->createComment();
        $this->patch(route('comments.reject', $comment->id))->assertStatus(403);
        $comment = $comment->fresh();
        $this->assertEquals(Comment::STATUS_NEW, $comment->status);
    }

    private function actionAsSuperAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    private function actionAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COMMENTS);
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

    private function createCourse()
    {
        $category = $this->createCategory();

        return Course::query()->create([
            'teacher_id' => auth()->id(),
            'category_id' => $category->id,
            'title' => fake()->title(),
            'slug' => fake()->slug(),
            'priority' => 5,
            'price' => 150000,
            'teacher_percent' => 75,
            'type' => Course::TYPE_FREE,
            'status' => Course::STATUS_COMPLETED,
        ]);
    }


    private function CourseData()
    {
        $category = $this->createCategory();
        return [
            'teacher_id' => auth()->id(),
            'category_id' => $category->id,
            'title' => fake()->title(),
            'slug' => fake()->slug(),
            'priority' => 5,
            'price' => 150000,
            'teacher_percent' => 75,
            'type' => Course::TYPE_FREE,
            'image' => UploadedFile::fake()->image('banner.jpg'),
            'status' => Course::STATUS_COMPLETED,
        ];
    }

    private function createCategory()
    {
        return Category::create([
            'title' => fake()->title,
            'slug' => fake()->slug
        ]);
    }

    private function createComment()
    {
        $course = $this->createCourse();
        return Comment::query()->create([
            'user_id' => auth()->id(),
            'body' => 'first my comment reply',
            'commentable_id' => $course->id,
            'commentable_type' => get_class($course)
        ]);
    }
}
