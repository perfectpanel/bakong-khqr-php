<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use PHPUnit\Framework\TestCase;

class TokenApiTest extends TestCase
{
	public function test_renew_token_api()
	{
		$response = BakongKHQR::renewToken('nonexistent-account@gmail.com', true);
		$this->assertEquals(10, $response['errorCode'], 'Unregistered email');
	}
}
