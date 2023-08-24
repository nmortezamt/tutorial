<?php

namespace Tutorial\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Illuminate\Support\Str;
use Tutorial\Category\Models\Category;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Models\Season;
use Tutorial\RolePermissions\Models\Permission;

class SeasonTest extends TestCase
{
    use RefreshDatabase;

    public function test_permitted_user_can_see_course_details_page()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->get(route('courses.details',$course->id))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->get(route('courses.details',$course->id))->assertOk();

        $this->actionAsSuperAdmin();
        $this->get(route('courses.details',$course->id))->assertOk();

    }

    public function test_not_permitted_user_can_not_see_course_details_page()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->actionAsUser();
        $this->get(route('courses.details',$course->id))->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('courses.details',$course->id))->assertStatus(403);

    }

    public function test_permitted_user_can_create_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(1,Season::count());

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title 2',
            'number'=>3
        ]);
        $this->assertEquals(2,Season::count());
        $this->assertEquals(3,Season::get()->last()->number);

    }

    public function test_not_permitted_user_can_not_create_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->actionAsUser();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ])->assertStatus(403);
        $this->assertEquals(0,Season::count());

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season 3'
        ])->assertStatus(403);
        $this->assertEquals(0,Season::count());
    }

    public function test_permitted_user_can_see_details_edit_page()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->get(route('seasons.edit',1))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->get(route('seasons.edit',1))->assertOk();
        $this->assertEquals(1,Season::count());

    }

    public function test_not_permitted_user_can_not_see_details_edit_page()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->actionAsUser();
        $this->get(route('seasons.edit',1))->assertStatus(403);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('seasons.edit',1))->assertStatus(403);
        $this->assertEquals(1,Season::count());
    }

    public function test_permitted_user_can_update_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(1,Season::count());
        $season = Season::first();
        $this->patch(route('seasons.update',$season->id),[
            'title'=>'title2'
        ]);
        $this->assertEquals('title2',Season::first()->title);

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->patch(route('seasons.update',$season->id),[
            'title'=>'title3',
            'number'=> 2,
        ]);
        $this->assertEquals('title3',Season::first()->title);
        $this->assertEquals(2,Season::get()->last()->number);

    }

    public function test_not_permitted_user_can_not_update_details()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->actionAsUser();
        $this->assertEquals(1,Season::count());
        $season = Season::first();
        $this->patch(route('seasons.update',$season->id),[
            'title'=>'title2'
        ])->assertStatus(403);
        $this->assertEquals('test season title',Season::first()->title);

        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->patch(route('seasons.update',$season->id),[
            'title'=>'title3',
            'number'=> 2,
        ]);
        $this->assertEquals('test season title',Season::first()->title);
    }

    public function test_permitted_user_can_delete_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(1,Season::count());
        $this->delete(route('seasons.destroy',1))->assertOk();
        $this->assertEquals(0,Season::count());

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(1,Season::count());
        $this->delete(route('seasons.destroy',2))->assertOk();
        $this->assertEquals(0,Season::count());
    }

    public function test_not_permitted_user_can_not_delete_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(1,Season::count());
        $this->actionAsUser();
        $this->delete(route('seasons.destroy',1))->assertStatus(403);
        $this->assertEquals(1,Season::count());
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->delete(route('seasons.destroy',1))->assertStatus(403);
        $this->assertEquals(1,Season::count());
    }

    public function test_permitted_user_can_accept_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING ,Season::find(1)->confirmation_status);
        $this->patch(route('seasons.accept',1))->assertOk();
        $this->assertEquals(Season::CONFIRMATION_STATUS_ACCEPTED,Season::find(1)->confirmation_status);

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title2'
        ]);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING ,Season::find(2)->confirmation_status);
        $this->patch(route('seasons.accept',2))->assertStatus(403);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING ,Season::find(2)->confirmation_status);
    }

    public function test_permitted_user_can_reject_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING,Season::find(1)->confirmation_status);
        $this->patch(route('seasons.reject',1))->assertOk();
        $this->assertEquals(Season::CONFIRMATION_STATUS_REJECTED,Season::find(1)->confirmation_status);

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title2'
        ]);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING ,Season::find(2)->confirmation_status);
        $this->patch(route('seasons.reject',2))->assertStatus(403);
        $this->assertEquals(Season::CONFIRMATION_STATUS_PENDING ,Season::find(2)->confirmation_status);
    }

    public function test_permitted_user_can_lock_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(Season::STATUS_OPENED,Season::find(1)->status);
        $this->patch(route('seasons.lock',1))->assertOk();
        $this->assertEquals(Season::STATUS_LOCKED,Season::find(1)->status);

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title2'
        ]);
        $this->assertEquals(Season::STATUS_OPENED ,Season::find(2)->status);
        $this->patch(route('seasons.lock',2))->assertStatus(403);
        $this->assertEquals(Season::STATUS_OPENED ,Season::find(2)->status);
    }

    public function test_permitted_user_can_unlock_season()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();

        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title'
        ]);
        $this->assertEquals(Season::STATUS_OPENED,Season::find(1)->status);
        $this->patch(route('seasons.unlock',1))->assertOk();
        $this->assertEquals(Season::STATUS_OPENED,Season::find(1)->status);

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course->teacher_id = auth()->id();
        $course->save();
        $this->post(route('seasons.store',$course->id),[
            'title'=>'test season title2'
        ]);
        $this->assertEquals(Season::STATUS_OPENED ,Season::find(2)->status);
        $this->patch(route('seasons.unlock',2))->assertStatus(403);
        $this->assertEquals(Season::STATUS_OPENED ,Season::find(2)->status);
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
            'discount'=> 120000,
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
            'discount'=> 120000,
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
