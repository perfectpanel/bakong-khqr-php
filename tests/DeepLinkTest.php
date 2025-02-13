<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use KHQR\Exceptions\KHQRException;
use KHQR\Models\SourceInfo;
use PHPUnit\Framework\TestCase;

class DeepLinkTest extends TestCase
{
    private function test_deep_link($data)
    {
        try {
            $response = BakongKHQR::generateDeepLink($data['data'][0], $data['data'][1], $data['data'][2] ?? null);
            $this->assertEquals($data['errorCode'], $response->status['errorCode'], $data['statement']);
        } catch (KHQRException $e) {
            $this->assertEquals($data['errorCode'], $e->getCode(), $data['statement']);
        }
    }

    public function test_deep_link_invalid_url()
    {
        $data = [
            'statement' => 'Invalid DeepLink URL',
            'data' => [
                'https://sit-api-bakong.nbc.gov.kh/deeplink',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF30',
            ],
            'errorCode' => 29,
        ];
        self::test_deep_link($data);
    }

    public function test_deep_link_source_info_null_callback()
    {
        $data = [
            'statement' => 'Null callback source info',
            'data' => [
                'https://sit-api-bakong.nbc.gov.kh/v1/generate_deeplink_by_qr',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF30',
                new SourceInfo(
                    'https://sit-api-bakong.nbc.gov.kh/khqr.png',
                    'KHQR',
                    null
                ),
            ],
            'errorCode' => 14,
        ];
        self::test_deep_link($data);
    }

    public function test_deep_link_source_info_null_icon()
    {
        $data = [
            'statement' => 'Null icon source info',
            'data' => [
                'https://sit-api-bakong.nbc.gov.kh/v1/generate_deeplink_by_qr',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF30',
                new SourceInfo(
                    null,
                    'KHQR',
                    'https://sit-api-bakong.nbc.gov.kh/callback'
                ),
            ],
            'errorCode' => 14,
        ];
        self::test_deep_link($data);
    }

    public function test_deep_link_source_info_null_name()
    {
        $data = [
            'statement' => 'Null name source info',
            'data' => [
                'https://sit-api-bakong.nbc.gov.kh/v1/generate_deeplink_by_qr',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF30',
                new SourceInfo(
                    'https://sit-api-bakong.nbc.gov.kh/khqr.png',
                    null,
                    'https://sit-api-bakong.nbc.gov.kh/callback'
                ),
            ],
            'errorCode' => 14,
        ];
        self::test_deep_link($data);
    }

    public function test_deep_link_invalid_qr()
    {
        $data = [
            'statement' => 'Invalid QR',
            'data' => [
                'https://sit-api-bakong.nbc.gov.kh/v1/generate_deeplink_by_qr',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF35',
            ],
            'errorCode' => 8,
        ];
        self::test_deep_link($data);
    }

    public function test_deep_link_timeout()
    {
        $data = [
            'statement' => 'Deeplink timeout',
            'data' => [
                'https://fake-deeplink-api.herokuapp.com/v1/generate_deeplink_by_qr',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF30',
            ],
            'errorCode' => 13,
        ];
        self::test_deep_link($data);
    }

    public function test_deep_link_success()
    {
        $data = [
            'statement' => 'Generating DeepLink successfully',
            'data' => [
                'https://sit-api-bakong.nbc.gov.kh/v1/generate_deeplink_by_qr',
                '00020101021229190015john_smith@devb5204599953038405405100.05802KH5910John Smith6010Phnom Penh6304BF30',
            ],
            'errorCode' => null,
        ];
        self::test_deep_link($data);
    }
}
