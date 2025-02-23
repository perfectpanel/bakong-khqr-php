<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use PHPUnit\Framework\TestCase;

class TokenApiTest extends TestCase
{
    public function test_renew_token_unregistered_email(): void
    {
        $response = BakongKHQR::renewToken('nonexistent-account@gmail.com');
        $this->assertEquals(10, $response['errorCode'], 'Unregistered email');
    }

    public function test_renew_token_registered_email(): void
    {
        $response = BakongKHQR::renewToken('fidele.fr@hotmail.com');
        $this->assertEquals($response['responseCode'], 0, 'Token has been issued');
        if (! isset($response['data']) || ! is_array($response['data']) || ! isset($response['data']['token'])) {
            $this->fail('[test_renew_token_registered_email] Unexpected data structure: '.json_encode($response));
        }
        $this->assertNotEmpty($response['data']['token'], 'Renewed token string is not empty');
    }
}
