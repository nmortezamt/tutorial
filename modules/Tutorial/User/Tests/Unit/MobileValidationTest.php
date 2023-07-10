<?php

namespace Tutorial\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tutorial\User\Rules\ValidMobile;

class MobileValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_mobile_can_not_less_than_11_character(): void
    {
        $result = preg_match("/^09\d{9}$/",'0911086903');
        $this->assertEquals(0,$result);
    }

    public function test_mobile_can_not_more_than_11_character(): void
    {
        $result = preg_match("/^09\d{9}$/",'099108690344');
        $this->assertEquals(0,$result);
    }

    public function test_mobile_should_start_by_zero_9(): void
    {
        $result = preg_match("/^09\d{9}$/",'12910869034');
        $this->assertEquals(0,$result);
    }
}
