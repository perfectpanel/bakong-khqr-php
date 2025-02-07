<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use KHQR\Exceptions\KHQRException;
use PHPUnit\Framework\TestCase;

class BakongAccountExistTest extends TestCase
{
    private $testData = [
        [
            'statement' => 'Account exists',
            'data' => [
                'url' => 'https://sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'dave@devb',
            ],
            'errorCode' => null,
        ],
        [
            'statement' => 'Account not found',
            'data' => [
                'url' => 'https://sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'dope@devb',
            ],
            'errorCode' => null,
        ],
        [
            'statement' => 'Invalid account length',
            'data' => [
                'url' => 'https://sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'dopedopedopedopedopedopedope@devb',
            ],
            'errorCode' => KHQRException::ERRORS[KHQRException::BAKONG_ACCOUNT_ID_LENGTH_INVALID][0],
        ],
        [
            'statement' => 'Account is invalid',
            'data' => [
                'url' => 'https://sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'davedevb',
            ],
            'errorCode' => KHQRException::ERRORS[KHQRException::BAKONG_ACCOUNT_ID_INVALID][0],
        ],
        [
            'statement' => 'Invalid URL',
            'data' => [
                'url' => 'https://sit-sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'dave@devb',
            ],
            'errorCode' => KHQRException::ERRORS[KHQRException::CONNECTION_TIMEOUT][0],
        ],
    ];

    protected function setUp(): void
    {
        putenv('NODE_TLS_REJECT_UNAUTHORIZED=0');
    }

    public function test_bakong_account_exist()
    {
        foreach ($this->testData as $data) {
            $checkAcc = BakongKHQR::checkBakongAccount($data['data']['url'], $data['data']['account']);
            $this->assertEquals($data['errorCode'], $checkAcc->status->errorCode, $data['statement']);
        }
    }
}
