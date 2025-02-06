<?php

declare(strict_types=1);

namespace KHQR\Helpers;

final class KHQRData
{
	public const CURRENCY_USD = 840;
	public const CURRENCY_KHR = 116;
	public const MERCHANT_TYPE_MERCHANT = "merchant";
	public const MERCHANT_TYPE_INDIVIDUAL = "individual";

	public const KHQRTag = [
		[
			'tag' => '00',
			'type' => 'payloadFormatIndicator',
			'required' => true,
		],
		[
			'tag' => '01',
			'type' => 'pointofInitiationMethod',
			'required' => false,
		],
		[
			'tag' => '15',
			'type' => 'unionPayMerchant',
			'required' => false,
		],
		[
			'sub' => true,
			'tag' => '29',
			'type' => 'globalUnqiueIdentifier',
			'required' => true,
		],
		[
			'tag' => '52',
			'type' => 'merchantCategoryCode',
			'required' => true,
		],
		[
			'tag' => '53',
			'type' => 'transactionCurrency',
			'required' => true,
		],
		[
			'tag' => '54',
			'type' => 'transactionAmount',
			'required' => false,
		],
		[
			'tag' => '58',
			'type' => 'countryCode',
			'required' => true,
		],
		[
			'tag' => '59',
			'type' => 'merchantName',
			'required' => true,
		],
		[
			'tag' => '60',
			'type' => 'merchantCity',
			'required' => true,
		],
		[
			'sub' => true,
			'tag' => '62',
			'type' => 'additionalData',
			'required' => false,
		],
		[
			'sub' => true,
			'tag' => '64',
			'type' => 'merchantInformationLanguageTemplate',
			'required' => false,
		],
		[
			'tag' => '99',
			'type' => 'timestamp',
			'required' => false,
		],
		[
			'tag' => '63',
			'type' => 'crc',
			'required' => true,
		],
	];

	public const KHQRSubtag = [
		'input' => [
			[
				'tag' => '29',
				'data' => [
					'bakongAccountID' => null,
					'accountInformation' => null,
				],
			],
			[
				'tag' => '30',
				'data' => [
					'bakongAccountID' => null,
					'merchantID' => null,
					'acquiringBank' => null,
				],
			],
			[
				'tag' => '62',
				'data' => [
					'billNumber' => null,
					'mobileNumber' => null,
					'storeLabel' => null,
					'terminalLabel' => null,
					'purposeOfTransaction' => null,
				],
			],
			[
				'tag' => '64',
				'data' => [
					'languagePreference' => null,
					'merchantNameAlternateLanguage' => null,
					'merchantCityAlternateLanguage' => null,
				],
			],
		],
		'compare' => [
			[
				'tag' => '29',
				'subTag' => emv::BAKONG_ACCOUNT_IDENTIFIER,
				'name' => 'bakongAccountID',
			],
			[
				'tag' => '29',
				'subTag' => emv::MERCHANT_ACCOUNT_INFORMATION_MERCHANT_ID,
				'name' => 'accountInformation',
			],
			[
				'tag' => '29',
				'subTag' => emv::MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK,
				'name' => 'acquiringBank',
			],
			[
				'tag' => '62',
				'subTag' => emv::BILLNUMBER_TAG,
				'name' => 'billNumber',
			],
			[
				'tag' => '62',
				'subTag' => emv::ADDITIONAL_DATA_FIELD_MOBILE_NUMBER,
				'name' => 'mobileNumber',
			],
			[
				'tag' => '62',
				'subTag' => emv::STORELABEL_TAG,
				'name' => 'storeLabel',
			],
			[
				'tag' => '62',
				'subTag' => emv::PURPOSE_OF_TRANSACTION,
				'name' => 'purposeOfTransaction',
			],
			[
				'tag' => '62',
				'subTag' => emv::TERMINAL_TAG,
				'name' => 'terminalLabel',
			],
			[
				'tag' => '64',
				'subTag' => emv::LANGUAGE_PREFERENCE,
				'name' => 'languagePreference',
			],
			[
				'tag' => '64',
				'subTag' => emv::MERCHANT_NAME_ALTERNATE_LANGUAGE,
				'name' => 'merchantNameAlternateLanguage',
			],
			[
				'tag' => '64',
				'subTag' => emv::MERCHANT_CITY_ALTERNATE_LANGUAGE,
				'name' => 'merchantCityAlternateLanguage',
			],
		],
	];
}
