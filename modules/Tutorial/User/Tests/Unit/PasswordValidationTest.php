<?php

namespace Tutorial\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tutorial\User\Rules\ValidPassword;

class PasswordValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_password_must_not_be_less_then_8_character(): void
    {
        $result = preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/','Mort@1a');
        $this->assertEquals(0,$result);
    }

    public function test_password_must_include_sing_character(): void
    {
        $result = preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/','Mortsdf1a');
        $this->assertEquals(0,$result);
    }

    public function test_password_must_include_digit_character(): void
    {
        $result = preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/','Mortsdf@a');
        $this->assertEquals(0,$result);
    }

        public function test_password_must_include_capital_character(): void
    {
        $result = preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/','mortsd@f1a');
        $this->assertEquals(0,$result);
    }

    public function test_password_must_include_small_character(): void
    {
        $result = preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/','MORTEA@1A');
        $this->assertEquals(0,$result);
    }
}
