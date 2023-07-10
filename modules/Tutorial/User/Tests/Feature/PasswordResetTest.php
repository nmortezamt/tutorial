<?php

namespace Tutorial\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tutorial\User\Models\User;
use Tutorial\User\Services\VerifyCodeService;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_see_passowrd_request_form(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    public function test_user_can_see_enter_verify_code_form_by_correct_email(): void
    {
        $user = User::create(
            [
                'name' => 'morteza',
                'email' => 'mortezanmt30@gmail.com',
                'password' => bcrypt('A!123agg'),
            ]
        );
        $this->call('get',route('password.reset.send'),['email'=>$user->email])->assertOk();
    }

    public function test_user_can_see_enter_verify_code_form_by_wrong_email(): void
    {
        $this->call('get',route('password.reset.send'),['email'=>'morteza.com'])->assertStatus(302);
    }

    public function test_user_banned_after_6_attempt_to_reset_password(): void
    {
        for($i=0;$i<5;$i++){
            $this->post(route('password.check.verify.code'),['verify_code','email'=>'morteza@gmail.com'])->assertStatus(302);
        }
        $this->post(route('password.check.verify.code'),['verify_code','email'=>'morteza@gmail.com'])->assertStatus(429);

    }
}
