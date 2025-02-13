<?php

declare(strict_types=1);

namespace KHQR\Api;

use KHQR\Config\Constants;
use KHQR\Helpers\Utils;

class Token
{
    public static function renewToken(string $email, bool $isTest = false)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        $url = $isTest ? Constants::SIT_RENEW_TOKEN_URL : Constants::RENEW_TOKEN_URL;

        return Utils::post_data_to_url($url, ['email' => $email]);
    }
}
