<?php

namespace Tutorial\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Illuminate\Support\Str;
use Tutorial\Category\Models\Category;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\Course\Models\Course;
use Tutorial\RolePermissions\Models\Permission;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    // user can see course index
    public function test_permitted_user_can_see_courses_index(): void
    {
        $this->actionAsAdmin();
        $this->get(route('courses.index'))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('courses.index'))->assertOk();

        $this->actionAsSuperAdmin();
        $this->get(route('courses.index'))->assertOk();

    }

    public function test_normal_user_can_not_see_courses_index(): void
    {
        $this->actionAsUser();
        $this->get(route('courses.index'))->assertStatus(403);
    }

    //usee can see create course form
    public function test_permitted_user_can_create_course_form(): void
    {
        $this->actionAsAdmin();
        $this->get(route('courses.create'))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('courses.create'))->assertOk();

    }

    public function test_normal_user_can_not_create_course_form(): void
    {
        $this->actionAsUser();
        $this->get(route('courses.create'))->assertStatus(403);

    }

    // user can create course
    public function test_permitted_user_can_create_course()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo([Permission::PERMISSION_MANAGE_OWN_COURSE,Permission::PERMISSION_MANAGE_TEACH]);
        $response = $this->post(route('courses.store'),$this->CourseData());
        $response->assertRedirect(route('courses.index'));
        $this->assertEquals(1,Course::count());
    }

    public function test_normal_user_can_not_create_course()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $response = $this->post(route('courses.store'),$this->CourseData());
        $response->assertStatus(403);
        $this->assertEquals(0,Course::count());
    }

    //user can see edit course form
    public function test_permitted_user_can_edit_course_form()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->get(route('courses.edit',$course->id))->assertOk();

        $this->actionAsUser();
        $course = $this->createCourse();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('courses.edit',$course->id))->assertOk();
    }

    public function test_permitted_user_can_not_edit_other_users_courses()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('courses.edit',$course->id))->assertStatus(403);
    }

    public function test_normal_user_can_not_edit_course_form(): void
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->get(route('courses.edit',$course->id))->assertStatus(403);

    }

    //user can update course

    public function test_permitted_user_can_update_course()
    {
        $this->actionAsUser();
        auth()->user()->givePermissionTo([Permission::PERMISSION_MANAGE_OWN_COURSE,Permission::PERMISSION_MANAGE_TEACH]);
        $course = $this->createCourse();
        $this->patch(route('courses.update',$course->id),[
            'teacher_id'=>auth()->id(),
            'category_id'=>$course->category->id,
            'title'=> 'new_title',
            'slug'=> fake()->slug(),
            'priority'=> 6,
            'price'=> 180000,
            'teacher_percent'=> 70,
            'type'=> Course::TYPE_CASH,
            'image'=>UploadedFile::fake()->image('banner.jpg'),
            'status'=> Course::STATUS_COMPLETED,
        ])->assertRedirect(route('courses.index'));
        $course = $course->fresh();
        $this->assertEquals('new_title',$course->title);
    }

    public function test_normal_user_can_not_update_course()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_TEACH);
        $this->patch(route('courses.update',$course->id),[
            'teacher_id'=>auth()->id(),
            'category_id'=>$course->category->id,
            'title'=> 'new_title',
            'slug'=> fake()->slug(),
            'priority'=> 6,
            'price'=> 180000,
            'teacher_percent'=> 70,
            'type'=> Course::TYPE_CASH,
            'image'=>UploadedFile::fake()->image('banner.jpg'),
            'status'=> Course::STATUS_COMPLETED,
        ])->assertStatus(403);
    }

    // user can delete course
    public function test_permitted_user_can_delete_course()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->delete(route('courses.destroy',$course->id))->assertOk();
        $this->assertEquals(0,Course::count());

    }

    public function test_normal_user_can_not_delete_course()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->delete(route('courses.destroy',$course->id))->assertStatus(403);
        $this->assertEquals(1,Course::count());

    }

    //user can change confirmation status course to accept
    public function test_permitted_user_can_change_confirmation_status_course_to_accept()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->patch(route('courses.accept',$course->id))->assertOk();
        $course = $course->fresh();
        $this->assertEquals(Course::CONFIRMATION_STATUS_ACCEPTED,$course->confirmation_status);

    }

    public function test_normal_user_can_not_change_confirmation_status_course_to_accept()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->patch(route('courses.accept',$course->id))->assertStatus(403);
        $course = $course->fresh();
        $this->assertEquals(Course::CONFIRMATION_STATUS_PENDING,$course->confirmation_status);

    }

    // user can change confirmation status course to reject
    public function test_permitted_user_can_change_confirmation_status_course_to_reject()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->patch(route('courses.reject',$course->id))->assertOk();
        $course = $course->fresh();
        $this->assertEquals(Course::CONFIRMATION_STATUS_REJECTED,$course->confirmation_status);

    }

    public function test_normal_user_can_not_change_confirmation_status_course_to_reject()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->patch(route('courses.reject',$course->id))->assertStatus(403);
        $course = $course->fresh();
        $this->assertEquals(Course::CONFIRMATION_STATUS_PENDING,$course->confirmation_status);

    }

    // user can change status course to lock

    public function test_permitted_user_can_change_status_course_to_lock()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->patch(route('courses.lock',$course->id))->assertOk();
        $course = $course->fresh();
        $this->assertEquals(Course::STATUS_LOCKED,$course->status);

    }

    public function test_normal_user_can_not_change_status_course_to_lock()
    {
        $this->actionAsUser();
        $course = $this->createCourse();
        $this->patch(route('courses.lock',$course->id))->assertStatus(403);
        $course = $course->fresh();
        $this->assertNotEquals(Course::STATUS_LOCKED,$course->status);

    }




    private function actionAsSuperAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    private function actionAsAdmin()
    {
        $this->createUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COURSE);
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
            'teacher_id'=>auth()->id(),
            'category_id'=>$category->id,
            'title'=> fake()->title(),
            'slug'=> fake()->slug(),
            'priority'=> 5,
            'price'=> 150000,
            'teacher_percent'=> 75,
            'type'=> Course::TYPE_FREE,
            'status'=> Course::STATUS_COMPLETED,
        ]);
    }


    private function CourseData()
    {
        $category = $this->createCategory();
        return [
            'teacher_id'=>auth()->id(),
            'category_id'=>$category->id,
            'title'=> fake()->title(),
            'slug'=> fake()->slug(),
            'priority'=> 5,
            'price'=> 150000,
            'teacher_percent'=> 75,
            'type'=> Course::TYPE_FREE,
            'image'=>UploadedFile::fake()->image('banner.jpg'),
            'status'=> Course::STATUS_COMPLETED,
        ];
    }

    private function createCategory()
    {
        return Category::create([
        'title' => fake()->title,
        'slug' => fake()->slug
        ]);
    }
}
