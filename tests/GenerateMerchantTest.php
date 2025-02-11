<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use PHPUnit\Framework\TestCase;
use KHQR\Models\MerchantInfo;

class GenerateMerchantTest extends TestCase
{
	private $testData = [
		[
			'statement' => 'Success Generate 1',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@devb',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
					'merchantId' => '123456',
					'acquiringBank' => 'Dev Bank',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 50000,
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
				],
			],
			'result' => '00020101021230400014jonhsmith@devb01061234560208Dev Bank5204599953031165405500005802KH5910Jonh Smith6009Siam Reap62550117INV-2021-07-658220211855123456780305BKK-10706012345',
		],
		[
			'statement' => 'Success Generate 2',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@devb',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
					'merchantId' => '123456',
					'acquiringBank' => 'Dev Bank',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 10,
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
				],
			],
			'result' => '00020101021230400014jonhsmith@devb01061234560208Dev Bank5204599953038405402105802KH5910Jonh Smith6010Phnom Penh62450117INV-2021-07-658220211855123456780305BKK-1',
		],
		[
			'statement' => 'Success Generate 3',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@devb',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
					'merchantId' => '123456',
					'acquiringBank' => 'Dev Bank',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 10,
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
				],
			],
			'result' => '00020101021230400014jonhsmith@devb01061234560208Dev Bank5204599953038405402105802KH5910Jonh Smith6010Phnom Penh62360117INV-2021-07-65822021185512345678',
		],
		[
			'statement' => 'Success Generate 4',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@devb',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
					'merchantId' => '123456',
					'acquiringBank' => 'Dev Bank',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 10,
					'mobileNumber' => '85512345678',
				],
			],
			'result' => '00020101021230400014jonhsmith@devb01061234560208Dev Bank5204599953038405402105802KH5910Jonh Smith6010Phnom Penh6215021185512345678',
		],
		[
			'statement' => 'Success Generate 5',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@devb',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
					'merchantId' => '123456',
					'acquiringBank' => 'Dev Bank',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 10,
				],
			],
			'result' => '00020101021230400014jonhsmith@devb01061234560208Dev Bank5204599953038405402105802KH5910Jonh Smith6010Phnom Penh',
		],
		[
			'statement' => 'Success Generate 6',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@devb',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
					'merchantId' => '123456',
					'acquiringBank' => 'Dev Bank',
				],
				'optional' => [
					'currency' => 840,
				],
			],
			'result' => '00020101021130400014jonhsmith@devb01061234560208Dev Bank5204599953038405802KH5910Jonh Smith6010Phnom Penh',
		],
	];

	private $amountTestData = [
		[
			'statement' => 'Amount test 1',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 100,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '100',
		],
		[
			'statement' => 'Amount test 2',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 100,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '100',
		],
		[
			'statement' => 'Amount test 3',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 100,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '100',
		],
		[
			'statement' => 'Amount test 4',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 9999999999,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '9999999999',
		],
		[
			'statement' => 'Amount test 5',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 10000,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '10000',
		],
		[
			'statement' => 'Amount test 6',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 1.12,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '1.12',
		],
		[
			'statement' => 'Amount test 7',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 1000,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '1000',
		],
		[
			'statement' => 'Amount test 8',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 100.11,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '100.11',
		],
		[
			'statement' => 'Amount test 9',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 100.12,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '100.12',
		],
		[
			'statement' => 'Amount test 10',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 12345678901,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '12345678901',
		],
		[
			'statement' => 'Amount test 11',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 9999999999.99,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '9999999999.99',
		],
		[
			'statement' => 'Name in UTF-8',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'ចន ស្មីន',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 100,
					'acquiringBank' => 'Dev Bank',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '100',
		],
	];

	public function test_generate_merchant_qr()
	{
		foreach ($this->testData as $data) {
			$requiredData = $data['data']['required'];
			$optionalData = $data['data']['optional'];

			$merchantInfo = MerchantInfo::withOptionalArray(
				$requiredData['bakongAccountID'],
				$requiredData['merchantName'],
				$requiredData['merchantCity'],
				$requiredData['merchantId'],
				$requiredData['acquiringBank'],
				$optionalData
			);

			$khqrData = BakongKHQR::generateMerchant($merchantInfo);
			$this->assertEquals($data['result'], substr($khqrData->data['qr'], 0, -29), $data['statement']);
		}
	}

	public function test_generate_merchant_amount()
	{
		foreach ($this->amountTestData as $data) {
			$requiredData = $data['data']['required'];
			$optionalData = $data['data']['optional'];

			$merchantInfo = MerchantInfo::withOptionalArray(
				$requiredData['bakongAccountID'],
				$requiredData['merchantName'],
				$requiredData['merchantCity'],
				'Dave',
				'Dave',
				$optionalData
			);

			$khqrData = BakongKHQR::generateMerchant($merchantInfo);
			$decodedKhqrData = BakongKHQR::decode($khqrData->data['qr']);
			// var_dump($decodedKhqrData);
			$this->assertEquals($data['result'], $decodedKhqrData->data['transactionAmount'], $data['statement']);
		}
	}
}
