<?

namespace KHQR;

class EMV
{
	public const PAYLOAD_FORMAT_INDICATOR = "00";
	public const DEFAULT_PAYLOAD_FORMAT_INDICATOR = "01";
	public const POINT_OF_INITIATION_METHOD = "01";
	public const STATIC_QR = "11";
	public const DYNAMIC_QR = "12";
	public const MERCHANT_ACCOUNT_INFORMATION_INDIVIDUAL = "29";
	public const MERCHANT_ACCOUNT_INFORMATION_MERCHANT = "30";
	public const BAKONG_ACCOUNT_IDENTIFIER = "00";
	public const MERCHANT_ACCOUNT_INFORMATION_MERCHANT_ID = "01";
	public const INDIVIDUAL_ACCOUNT_INFORMATION = "01";
	public const MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK = "02";
	public const MERCHANT_CATEGORY_CODE = "52";
	public const DEFAULT_MERCHANT_CATEGORY_CODE = "5999";
	public const TRANSACTION_CURRENCY = "53";
	public const TRANSACTION_AMOUNT = "54";
	public const DEFAULT_TRANSACTION_AMOUNT = "0";
	public const COUNTRY_CODE = "58";
	public const DEFAULT_COUNTRY_CODE = "KH";
	public const MERCHANT_NAME = "59";
	public const MERCHANT_CITY = "60";
	public const DEFAULT_MERCHANT_CITY = "Phnom Penh";
	public const CRC = "63";
	public const CRC_LENGTH = "04";
	public const ADDITIONAL_DATA_TAG = "62";
	public const BILLNUMBER_TAG = "01";
	public const ADDITIONAL_DATA_FIELD_MOBILE_NUMBER = "02";
	public const STORELABEL_TAG = "03";
	public const TERMINAL_TAG = "07";
	public const PURPOSE_OF_TRANSACTION = "08";
	public const TIMESTAMP_TAG = "99";
	public const MERCHANT_INFORMATION_LANGUAGE_TEMPLATE = "64";
	public const LANGUAGE_PREFERENCE = "00";
	public const MERCHANT_NAME_ALTERNATE_LANGUAGE = "01";
	public const MERCHANT_CITY_ALTERNATE_LANGUAGE = "02";
	public const UNIONPAY_MERCHANT_ACCOUNT = "15";

	// Invalid length
	public const INVALID_LENGTH_KHQR = 12;
	public const INVALID_LENGTH_MERCHANT_NAME = 25;
	public const INVALID_LENGTH_BAKONG_ACCOUNT = 32;
	public const INVALID_LENGTH_AMOUNT = 13;
	public const INVALID_LENGTH_COUNTRY_CODE = 3;
	public const INVALID_LENGTH_MERCHANT_CATEGORY_CODE = 4;
	public const INVALID_LENGTH_MERCHANT_CITY = 15;
	public const INVALID_LENGTH_TIMESTAMP = 13;
	public const INVALID_LENGTH_TRANSACTION_AMOUNT = 14;
	public const INVALID_LENGTH_TRANSACTION_CURRENCY = 3;
	public const INVALID_LENGTH_BILL_NUMBER = 25;
	public const INVALID_LENGTH_STORE_LABEL = 25;
	public const INVALID_LENGTH_TERMINAL_LABEL = 25;
	public const INVALID_LENGTH_PURPOSE_OF_TRANSACTION = 25;
	public const INVALID_LENGTH_MERCHANT_ID = 32;
	public const INVALID_LENGTH_ACQUIRING_BANK = 32;
	public const INVALID_LENGTH_MOBILE_NUMBER = 25;
	public const INVALID_LENGTH_ACCOUNT_INFORMATION = 32;
	public const INVALID_LENGTH_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE = 99;
	public const INVALID_LENGTH_UPI_MERCHANT = 99;
	public const INVALID_LENGTH_LANGUAGE_PREFERENCE = 2;
	public const INVALID_LENGTH_MERCHANT_NAME_ALTERNATE_LANGUAGE = 25;
	public const INVALID_LENGTH_MERCHANT_CITY_ALTERNATE_LANGUAGE = 15;
}

class KHQRData
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
