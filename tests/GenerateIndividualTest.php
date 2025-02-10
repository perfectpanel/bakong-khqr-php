<?php

declare(strict_types=1);

namespace KHQR\Tests;

use KHQR\BakongKHQR;
use PHPUnit\Framework\TestCase;
use KHQR\Models\IndividualInfo;

class GenerateIndividualTest extends TestCase
{
	private $testData = [
		[
			'statement' => 'Success Generate 1',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'PHNOM PENH',
				],
				'optional' => [
					'currency' => 840,
					'amount' => 1,
					'billNumber' => 'INV-2021-07-65822',
				],
			],
			'result' => '00020101021229180014jonhsmith@nbcq520459995303840540115802KH5910Jonh Smith6010PHNOM PENH62210117INV-2021-07-65822',
		],
		[
			'statement' => 'Success Generate 2',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 50000,
					'mobileNumber' => '85512345678',
				],
			],
			'result' => '00020101021229180014jonhsmith@nbcq5204599953031165405500005802KH5910Jonh Smith6010Phnom Penh6215021185512345678',
		],
		[
			'statement' => 'Success Generate 3',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
				],
				'optional' => [
					'amount' => 50000,
					'storeLabel' => 'BKK-1',
				],
			],
			'result' => '00020101021229180014jonhsmith@nbcq5204599953031165405500005802KH5910Jonh Smith6010Phnom Penh62090305BKK-1',
		],
		[
			'statement' => 'Success Generate 4',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
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
			'result' => '00020101021229180014jonhsmith@nbcq5204599953031165405500005802KH5910Jonh Smith6009Siam Reap62550117INV-2021-07-658220211855123456780305BKK-10706012345',
		],
		[
			'statement' => 'Success Generate 5',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 50000,
					'acquiringBank' => 'Dev Bank',
					'accountInformation' => '012345678',
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
				],
			],
			'result' => '00020101021229430014jonhsmith@nbcq01090123456780208Dev Bank5204599953031165405500005802KH5910Jonh Smith6009Siam Reap62550117INV-2021-07-658220211855123456780305BKK-10706012345',
		],
		[
			'statement' => 'Success Generate 6',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 50000,
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'acquiringBank' => 'Dev Bank',
				],
			],
			'result' => '00020101021229300014jonhsmith@nbcq0208Dev Bank5204599953031165405500005802KH5910Jonh Smith6009Siam Reap62550117INV-2021-07-658220211855123456780305BKK-10706012345',
		],
		[
			'statement' => 'Success Generate 7',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 50000,
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'acquiringBank' => 'Dev Bank',
				],
			],
			'result' => '00020101021229300014jonhsmith@nbcq0208Dev Bank5204599953031165405500005802KH5910Jonh Smith6009Siam Reap62550117INV-2021-07-658220211855123456780305BKK-10706012345',
		],
		[
			'statement' => 'Success Generate 8',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'currency' => 116,
					'amount' => 50000,
					'mobileNumber' => '85512345678',
					'billNumber' => 'INV-2021-07-65822',
					'storeLabel' => 'BKK-1',
					'terminalLabel' => '012345',
					'accountInformation' => '012345678',
				],
			],
			'result' => '00020101021229310014jonhsmith@nbcq01090123456785204599953031165405500005802KH5910Jonh Smith6009Siam Reap62550117INV-2021-07-658220211855123456780305BKK-10706012345',
		],
		[
			'statement' => 'Success Generate 9',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Siam Reap',
				],
				'optional' => [
					'languagePreference' => 'km',
					'merchantNameAlternateLanguage' => 'ចន ស្មីន',
					'merchantCityAlternateLanguage' => 'សៀមរាប',
				],
			],
			'result' => '00020101021129180014jonhsmith@nbcq5204599953031165802KH5910Jonh Smith6009Siam Reap64280002km0108ចន ស្មីន0206សៀមរាប',
		],
		[
			'statement' => 'Success Generate 10',
			'data' => [
				'required' => [
					'bakongAccountID' => 'jonhsmith@nbcq',
					'merchantName' => 'Jonh Smith',
					'merchantCity' => 'Phnom Penh',
				],
				'optional' => [
					'mobileNumber' => '85512345678',
					'purposeOfTransaction' => 'Testing',
				],
			],
			'result' => '00020101021129180014jonhsmith@nbcq5204599953031165802KH5910Jonh Smith6010Phnom Penh62260211855123456780807Testing',
		],
	];

	public function test_generate_individual_qr()
	{
		foreach ($this->testData as $data) {
			$requiredData = $data['data']['required'];
			$optionalData = $data['data']['optional'];

			$individualInfo = IndividualInfo::withOptionalArray(
				$requiredData['bakongAccountID'],
				$requiredData['merchantName'],
				$requiredData['merchantCity'],
				$optionalData
			);

			$khqrData = BakongKHQR::generateIndividual($individualInfo);
			$this->assertEquals($data['result'], substr($khqrData->data['qr'], 0, -29), $data['statement']);
		}
	}
}
