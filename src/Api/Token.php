<?php

declare(strict_types=1);

namespace KHQR\Api;

use Constants;
use KHQR\Helpers\Utils;

class Token
{
	public static function renewToken(string $email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException("Invalid email address");
		}

		return Utils::post_data_to_url(Constants::BAKONG_API_TOKEN_RENEW_URL, ['email' => $email]);
	}
}
