<?php

declare(strict_types=1);

namespace KHQR\Api;

use Constants;
use KHQR\Helpers\Utils;

class Transaction
{
    public static function checkTransactionByMD5(string $token, string $md5)
    {
        if (Utils::isBlank($token) || Utils::isBlank($md5)) {
            throw new \InvalidArgumentException('Token and MD5 cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_MD5_URL, ['md5' => $md5], $token);
    }

    public static function checkTransactionByMD5List(string $token, array $md5Array)
    {
        if (Utils::isBlank($token) || empty($md5Array)) {
            throw new \InvalidArgumentException('Token and MD5 array cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_MD5_LIST_URL, $md5Array, $token);
    }

    public static function checkTransactionByFullHash(string $token, string $fullHash)
    {
        if (Utils::isBlank($token) || Utils::isBlank($fullHash)) {
            throw new \InvalidArgumentException('Token and Hash cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_FULL_HASH_URL, ['hash' => $fullHash], $token);
    }

    public static function checkTransactionByFullHashList(string $token, array $fullHashArray)
    {
        if (Utils::isBlank($token) || empty($fullHashArray)) {
            throw new \InvalidArgumentException('Token and Hash array cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_FULL_HASH_LIST_URL, $fullHashArray, $token);
    }

    public static function checkTransactionByShortHash(string $token, string $hash, string $amount, string $currency)
    {
        if (Utils::isBlank($token) || Utils::isBlank($hash)) {
            throw new \InvalidArgumentException('Token, hash, amount and currency cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_SHORT_HASH_URL, ['hash' => $hash, 'amount' => $amount, 'currency' => $currency], $token);
    }

    public static function checkTransactionByInstructionReference(string $token, string $ref)
    {
        if (Utils::isBlank($token) || Utils::isBlank($ref)) {
            throw new \InvalidArgumentException('Token and Reference cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_INSTRUCTION_REF_URL, ['instructionRef' => $ref], $token);
    }

    public static function checkTransactionByExternalReference(string $token, string $ref)
    {
        if (Utils::isBlank($token) || Utils::isBlank($ref)) {
            throw new \InvalidArgumentException('Token and Reference cannot be blank');
        }

        return Utils::post_data_to_url(Constants::BAKONG_API_CHECK_TRANSACTION_EXTERNAL_REF_URL, ['externalRef' => $ref], $token);
    }
}
