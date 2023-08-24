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
use Tutorial\Course\Models\Lesson;
use Tutorial\Course\Models\Season;
use Tutorial\RolePermissions\Models\Permission;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_permitted_user_can_see_create_lesson_form()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->get(route('lessons.create',$course->id))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course = $this->createCourse();
        $this->get(route('lessons.create',$course->id))->assertOk();

    }

    public function test_not_permitted_user_can_not_create_lesson_form()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('lessons.create',$course->id))->assertStatus(403);

    }

    public function test_permitted_user_can_store_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->post(route('lessons.store',$course->id),[
            'title'=>fake()->title,
            'time'=>15,
            'is_free'=> 1,
            'lesson_file'=>UploadedFile::fake()->create('test.mp4',10240)
        ]);
        $this->assertEquals(1,Lesson::query()->count());

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course = $this->createCourse();
        $this->post(route('lessons.store',$course->id),[
            'title'=>fake()->title,
            'time'=>15,
            'is_free'=> 1,
            'lesson_file'=>UploadedFile::fake()->create('test.mp4',10240)
        ]);
        $this->assertEquals(2,Lesson::query()->count());
    }

    public function test_not_permitted_user_can_not_store_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->post(route('lessons.store',$course->id),[
            'title'=>fake()->title,
            'time'=>15,
            'is_free'=> 1,
            'lesson_file'=>UploadedFile::fake()->create('test.mp4',10240)
        ]);
        $this->assertEquals(0,Lesson::query()->count());

    }

    public function test_only_allowed_extensions_can_be_uploaded()
    {
        $notAllowedExtensions = ['png','jpg','mp3'];

        $this->actionAsAdmin();
        $course = $this->createCourse();
        foreach($notAllowedExtensions as $extensions)
        {
            $this->post(route('lessons.store',$course->id),[
                'title'=>fake()->title,
                'time'=>15,
                'is_free'=> 1,
                'lesson_file'=>UploadedFile::fake()->create('test.'.$extensions,10240)
            ]);
        }
        $this->assertEquals(0,Lesson::query()->count());
    }

    public function test_permitted_user_can_see_edit_lesson_form()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->get(route('lessons.edit',[$course->id,$lesson->id]))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->get(route('lessons.edit',[$course->id,$lesson->id]))->assertOk();

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->get(route('lessons.edit',[$course->id,$lesson->id]))->assertStatus(403);

    }

    public function test_permitted_user_can_update_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->patch(route('lessons.update',[$course->id,$lesson->id]),[
            'title'=>'updated title',
            'time'=>15,
            'is_free'=>0
        ]);
        $this->assertEquals('updated title',Lesson::find(1)->title);

        $this->actionAsUser();
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSE);
        $this->patch(route('lessons.update',[$course->id,$lesson->id]),[
            'title'=>'updated title 2',
            'time'=>15,
            'is_free'=>1
        ])->assertStatus(403);
        $this->assertEquals('updated title',Lesson::find(1)->title);
    }

    public function test_permitted_user_can_accept_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(1)->confirmation_status);
        $this->patch(route('lessons.accept',1));
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED,Lesson::find(1)->confirmation_status);

        $this->actionAsUser();
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(2)->confirmation_status);
        $this->patch(route('lessons.accept',2))->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(2)->confirmation_status);

    }

    public function test_permitted_user_can_acceptAll_lessons()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $course2 = $this->createCourse();
        $this->createLesson($course2);

        $this->assertEquals($course->lessons()->count(),$course->lessons()->where('confirmation_status',Lesson::CONFIRMATION_STATUS_PENDING)->count());
        $this->patch(route('lessons.acceptAll',$course->id));
        $this->assertEquals($course->lessons()->count(),$course->lessons()->where('confirmation_status',Lesson::CONFIRMATION_STATUS_ACCEPTED)->count());

        $this->actionAsUser();
        $this->assertEquals($course2->lessons()->count(),$course2->lessons()->where('confirmation_status',Lesson::CONFIRMATION_STATUS_PENDING)->count());
        $this->patch(route('lessons.acceptAll',$course2->id))->assertStatus(403);
        $this->assertEquals($course2->lessons()->count(),$course2->lessons()->where('confirmation_status',Lesson::CONFIRMATION_STATUS_PENDING)->count());

    }

    public function test_permitted_user_can_accept_multiple_lessons()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.acceptMultiple',$course->id),[
            'ids'=>'1,2'
        ]);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED,Lesson::find(1)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED,Lesson::find(2)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(3)->confirmation_status);

        $this->actionAsUser();
        $this->patch(route('lessons.acceptMultiple',$course->id),[
            'ids'=>'3'
        ])->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(3)->confirmation_status);

    }

    public function test_permitted_user_can_reject_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(1)->confirmation_status);
        $this->patch(route('lessons.reject',1));
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED,Lesson::find(1)->confirmation_status);

        $this->actionAsUser();
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(2)->confirmation_status);
        $this->patch(route('lessons.reject',2))->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(2)->confirmation_status);

    }

    public function test_permitted_user_can_reject_multiple_lessons()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.rejectMultiple',$course->id),[
            'ids'=>'1,2'
        ]);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED,Lesson::find(1)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED,Lesson::find(2)->confirmation_status);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(3)->confirmation_status);

        $this->actionAsUser();
        $this->patch(route('lessons.rejectMultiple',$course->id),[
            'ids'=>'3'
        ])->assertStatus(403);
        $this->assertEquals(Lesson::CONFIRMATION_STATUS_PENDING,Lesson::find(3)->confirmation_status);
    }

    public function test_permitted_user_can_lock_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->assertEquals(Lesson::STATUS_OPENED,Lesson::find(1)->status);
        $this->patch(route('lessons.lock',1));
        $this->assertEquals(Lesson::STATUS_LOCKED,Lesson::find(1)->status);

        $this->actionAsUser();
        $this->assertEquals(Lesson::STATUS_OPENED,Lesson::find(2)->status);
        $this->patch(route('lessons.lock',2))->assertStatus(403);
        $this->assertEquals(Lesson::STATUS_OPENED,Lesson::find(2)->status);

    }

    public function test_permitted_user_can_unlock_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.lock',1));
        $this->patch(route('lessons.lock',2));
        $this->assertEquals(Lesson::STATUS_LOCKED,Lesson::find(1)->status);
        $this->patch(route('lessons.unlock',1));
        $this->assertEquals(Lesson::STATUS_OPENED,Lesson::find(1)->status);

        $this->actionAsUser();
        $this->assertEquals(Lesson::STATUS_LOCKED,Lesson::find(2)->status);
        $this->patch(route('lessons.unlock',2))->assertStatus(403);
        $this->assertEquals(Lesson::STATUS_LOCKED,Lesson::find(2)->status);

    }

    public function test_permitted_user_can_delete_lesson()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->delete(route('lessons.destroy',1));
        $this->assertEquals(null,Lesson::find(1));
        $this->actionAsUser();
        $this->delete(route('lessons.destroy',2))->assertStatus(403);
        $this->assertEquals(1,Lesson::where('id',2)->count());

    }

    public function test_permitted_user_can_delete_multiple_lessons()
    {
        $this->actionAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->createLesson($course);
        $this->delete(route('lessons.destroyMultiple',$course->id),[
            'ids'=>'1,2'
        ]);
        $this->assertEquals(1,Lesson::count());
        $this->actionAsUser();
        $this->delete(route('lessons.destroyMultiple',$course->id),[
            'ids'=>'3'
        ])->assertStatus(403);
        $this->assertEquals(1,Lesson::count());

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

    private function createLesson($course)
    {
        return Lesson::create([
            'title'=>"lesson one",
            'slug'=>"lesson one",
            'time'=>15,
            'number'=>1,
            'course_id'=>$course->id,
            'user_id'=>auth()->id()
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
