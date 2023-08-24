<?php

namespace Tutorial\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tutorial\RolePermissions\Database\seeders\RolePermissionTableSeeder;
use Tutorial\User\Models\User;
use Tutorial\User\Services\VerifyCodeService;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_see_register_form(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_can_register()
    {
        $response = $this->RegisterNewUser();

        $response->assertRedirect(route('dashboard.index'));
        $this->assertCount(1 , User::all());
    }

    public function test_user_have_to_verify_account()
    {
        $this->RegisterNewUser();

        $response = $this->get(route('dashboard.index'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_user_can_verify_account()
    {
        $user = User::create(
            [
                'name' => 'morteza',
                'email' => 'morteza@gmail.com',
                'password' => bcrypt('A!123agg'),
            ]
        );
        $code = VerifyCodeService::generate();
        VerifyCodeService::store($user->id, $code, now()->addDay());

        auth()->loginUsingId($user->id);

        $this->assertAuthenticated();

        $this->post(route('verification.verify'), [
            'verify_code' => $code
        ]);

        $this->assertEquals(true, $user->fresh()->hasVerifiedEmail());
    }

    public function test_verified_user_can_see_dashboard_page()
    {
        $this->seed(RolePermissionTableSeeder::class);
        $this->RegisterNewUser();
        $this->assertAuthenticated();
        auth()->user()->markEmailAsVerified();
        $response = $this->get(route('dashboard.index'));
        $response->assertOk();
    }

    public function RegisterNewUser()
    {
        return $this->post(route('register'),[
            'name'=>'mroteza',
            'email'=>'morteza2@gmail.com',
            'password'=>'mortezaQ@1',
            'password_confirmation'=>'mortezaQ@1'
        ]);
    }
}
