<?php

namespace Tutorial\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tutorial\User\Models\User;

class LoginTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     public function test_can_see_login_form()
     {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
     }
    public function test_user_can_login_by_email(): void
    {
        $user = User::create([
            'name'=>$this->faker->name,
            'email'=>$this->faker->safeEmail,
            'password'=> bcrypt('mortezaQ@1')
        ]);

        $this->post(route('login'),[
            'email_mobile'=> $user->email,
            'password'=>'mortezaQ@1'
        ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_login_by_mobile(): void
    {
        $user = User::create([
            'name'=>$this->faker->name,
            'email'=>$this->faker->safeEmail,
            'mobile'=>'09228647422',
            'password'=> bcrypt('mortezaQ@1')
        ]);

        $this->post(route('login'),[
            'email_mobile'=> $user->mobile,
            'password'=>'mortezaQ@1'
        ]);

        $this->assertAuthenticated();
    }
}
