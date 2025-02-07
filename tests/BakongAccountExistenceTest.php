<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use KHQR\Exceptions\KHQRException;
use PHPUnit\Framework\TestCase;

class BakongAccountExistenceTest extends TestCase
{
    private $testData = [
        [
            'statement' => 'Account exists',
            'data' => [
                'url' => 'https://sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'dave@devb',
            ],
            'errorCode' => null,
            'bakongAccountExists' => true,
        ],
        [
            'statement' => 'Account not found',
            'data' => [
                'url' => 'https://sit-api-bakong.nbc.gov.kh/v1/check_account_exist',
                'account' => 'dope@devb',
            ],
            'errorCode' => null,
            'bakongAccountExists' => false,
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

    public function test_bakong_account_existence()
    {
        foreach ($this->testData as $data) {
            try {
                $checkAcc = BakongKHQR::checkBakongAccount($data['data']['url'], $data['data']['account']);
                $this->assertEquals($data['errorCode'], $checkAcc->status->errorCode, $data['statement']);
                $this->assertEquals($data['bakongAccountExists'], $checkAcc->data->bakongAccountExists, $data['statement']);
            } catch (KHQRException $e) {
                $this->assertEquals($data['errorCode'], $e->getCode(), $data['statement']);
            }
        }
    }
}
