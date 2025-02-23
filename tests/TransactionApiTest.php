<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use KHQR\Exceptions\KHQRException;
use PHPUnit\Framework\TestCase;

class TransactionApiTest extends TestCase
{
    private string $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiN2Y0NTBhZTIzYWRhNDE1MCJ9LCJpYXQiOjE3NDAyNzQ3MzMsImV4cCI6MTc0ODA1MDczM30.zywqMB3R9aILpi37MWgQhMPfYlPxBHg2-amwcnyb34w';

    private string $expiredToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiMWExMTJhNGE2NWZhNGNkNSJ9LCJpYXQiOjE3MjgxNjg1OTAsImV4cCI6MTczNTk0NDU5MH0.TW2cWPrcPTWTuR-Hth_6tXnNLoTjQVBknuyRlMQIluk';

    private function renew_token() {
        $response = BakongKHQR::renewToken('jake.p3ralta@gmail.com');
        return $response['data']['token'];
    }

    public function test_expired_token()
    {
        $bakongKhqr = new BakongKHQR($this->expiredToken);
        try {
            $bakongKhqr->checkTransactionByMD5('d60f3db96913029a2af979a1662c1e72', true);
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
        }
    }

    public function test_check_transaction_by_md5()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByMD5('d60f3db96913029a2af979a1662c1e72', true);
            $this->assertEquals(1, $response['errorCode']);
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByMD5('d60f3db96913029a2af979a1662c1e72', true);
                $this->assertEquals(1, $response['errorCode']);
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }

    public function test_check_transaction_by_full_hash()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByFullHash('dcd53430d3b3005d9cda36f1fe8dedc3714ccf18f886cf5d090d36fee67ef956', true);
            $this->assertEquals(1, $response['errorCode']);
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByFullHash('dcd53430d3b3005d9cda36f1fe8dedc3714ccf18f886cf5d090d36fee67ef956', true);
                $this->assertEquals(1, $response['errorCode']);
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }

    public function test_check_transaction_by_short_hash()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByShortHash('8465d722', 1.0, 'USD', true);
            $this->assertEquals(1, $response['errorCode']);
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByShortHash('8465d722', 1.0, 'USD', true);
                $this->assertEquals(1, $response['errorCode']);
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }

    public function test_check_transaction_by_instruction_ref()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByInstructionReference('00001234', true);
            $this->assertEquals(1, $response['errorCode']);
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByInstructionReference('00001234', true);
                $this->assertEquals(1, $response['errorCode']);
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }

    public function test_check_transaction_by_external_ref()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByExternalReference('DEV123456ZTH', true);
            $this->assertEquals(1, $response['errorCode']);
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByExternalReference('DEV123456ZTH', true);
                $this->assertEquals(1, $response['errorCode']);
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }

    public function test_check_transaction_by_md5_list()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByMD5List([
                '0dbe08d3829a8b6b59844e51aa38a4e2',
                '7b0e5c36486d7155eb3ee94997fe9bfb',
                'e12b3ecc4c066405ce05cd8cacab884c',
            ], true);
            $notFoundArray = array_filter($response['data'], fn ($item) => $item['status'] === 'NOT_FOUND');
            $this->assertEquals(3, count($notFoundArray));
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByMD5List([
                    '0dbe08d3829a8b6b59844e51aa38a4e2',
                    '7b0e5c36486d7155eb3ee94997fe9bfb',
                    'e12b3ecc4c066405ce05cd8cacab884c',
                ], true);
                $notFoundArray = array_filter($response['data'], fn ($item) => $item['status'] === 'NOT_FOUND');
                $this->assertEquals(3, count($notFoundArray));
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }

    public function test_check_transaction_by_hash_list()
    {
        $bakongKhqr = new BakongKHQR($this->token);
        try {
            $response = $bakongKhqr->checkTransactionByFullHashList([
                'f0ae142842181535e678900bc5be1c3bd48d567ced77410a169fb672792968c8',
                'd3b42e35d618a42b7506a79564083e6e91d5383b63f8aa2cf2ca7e65d55ec858',
                '9036688e95cb3d1b621a9a989ebe64629d8c118654cfbc47f4d4991d72fc3b44',
            ], true);
            $notFoundArray = array_filter($response['data'], fn ($item) => $item['status'] === 'NOT_FOUND');
            $this->assertEquals(3, count($notFoundArray));
        } catch (KHQRException $e) {
            $this->assertEquals(6, $e->getCode());
            try {
                $new_token = self::renew_token();
                $bakongKhqr = new BakongKHQR($new_token);
                $response = $bakongKhqr->checkTransactionByFullHashList([
                    'f0ae142842181535e678900bc5be1c3bd48d567ced77410a169fb672792968c8',
                    'd3b42e35d618a42b7506a79564083e6e91d5383b63f8aa2cf2ca7e65d55ec858',
                    '9036688e95cb3d1b621a9a989ebe64629d8c118654cfbc47f4d4991d72fc3b44',
                ], true);
                $notFoundArray = array_filter($response['data'], fn ($item) => $item['status'] === 'NOT_FOUND');
                $this->assertEquals(3, count($notFoundArray));
            } catch (KHQRException $e) {
                $this->fail("Test failed due to an unexpected exception: " . $e->getMessage());
            }
        }
    }
}
