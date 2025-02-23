<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use PHPUnit\Framework\TestCase;

class TokenApiTest extends TestCase
{
    public function test_renew_token_unregistered_email()
    {
        $response = BakongKHQR::renewToken('nonexistent-account@gmail.com', true);
        $this->assertEquals(10, $response['errorCode'], 'Unregistered email');
    }

    public function test_renew_token_registered_email() {
        $response = BakongKHQR::renewToken('jake.p3ralta@gmail.com', true);
        $this->assertEquals($response['responseCode'], 0, 'Token has been issued');
        $this->assertNotEmpty($response['data']['token'], 'Renewed token string is not empty');
    }
}
