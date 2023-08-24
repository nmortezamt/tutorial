<?php

namespace Tutorial\User\Tests\Unit;

use Tests\TestCase;
use Tutorial\User\Services\VerifyCodeService;

class VerifyCodeServiceTest extends TestCase
{

    public function test_generated_code_is_6_digit(): void
    {
        $code = VerifyCodeService::generate();
        $this->assertIsNumeric($code,'generated code is not numeric');
        $this->assertLessThanOrEqual(999999,$code,'generated code is less than 999999');
        $this->assertGreaterThanOrEqual(100000,$code,'generated code is more than 100000');
    }

    public function test_verify_code_can_store()
    {
        $code = VerifyCodeService::generate();
        VerifyCodeService::store(1,$code,now()->addHour());
        $this->assertEquals($code,cache()->get('verify_code_1'));
    }

}
