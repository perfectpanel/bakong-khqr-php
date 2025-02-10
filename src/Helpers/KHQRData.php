<?php

declare(strict_types=1);

namespace KHQR\Helpers;

final class KHQRData
{
    public const CURRENCY_USD = 840;

    public const CURRENCY_KHR = 116;

    public const MERCHANT_TYPE_MERCHANT = 'merchant';

    public const MERCHANT_TYPE_INDIVIDUAL = 'individual';

    public const KHQRTag = [
        [
            'tag' => '00',
            'type' => 'payloadFormatIndicator',
            'required' => true,
            'instance' => 'KHQR\Models\PayloadFormatIndicator'
        ],
        [
            'tag' => '01',
            'type' => 'pointofInitiationMethod',
            'required' => false,
            'instance' => 'KHQR\Models\PointOfInitiationMethod'
        ],
        [
            'tag' => '15',
            'type' => 'unionPayMerchant',
            'required' => false,
            'instance' => 'KHQR\Models\UnionpayMerchantAccount'
        ],
        [
            'sub' => true,
            'tag' => '29',
            'type' => 'globalUnqiueIdentifier',
            'required' => true,
            'instance' => 'KHQR\Models\GlobalUniqueIdentifier'
        ],
        [
            'tag' => '52',
            'type' => 'merchantCategoryCode',
            'required' => true,
            'instance' => 'KHQR\Models\MerchantCategoryCode'
        ],
        [
            'tag' => '53',
            'type' => 'transactionCurrency',
            'required' => true,
            'instance' => 'KHQR\Models\TransactionCurrency'
        ],
        [
            'tag' => '54',
            'type' => 'transactionAmount',
            'required' => false,
            'instance' => 'KHQR\Models\TransactionAmount'
        ],
        [
            'tag' => '58',
            'type' => 'countryCode',
            'required' => true,
            'instance' => 'KHQR\Models\CountryCode'
        ],
        [
            'tag' => '59',
            'type' => 'merchantName',
            'required' => true,
            'instance' => 'KHQR\Models\MerchantName'
        ],
        [
            'tag' => '60',
            'type' => 'merchantCity',
            'required' => true,
            'instance' => 'KHQR\Models\MerchantCity'
        ],
        [
            'sub' => true,
            'tag' => '62',
            'type' => 'additionalData',
            'required' => false,
            'instance' => 'KHQR\Models\AdditionalData'
        ],
        [
            'sub' => true,
            'tag' => '64',
            'type' => 'merchantInformationLanguageTemplate',
            'required' => false,
            'instance' => 'KHQR\Models\MerchantInformationLanguageTemplate'
        ],
        [
            'tag' => '99',
            'type' => 'timestamp',
            'required' => false,
            'instance' => 'KHQR\Models\TimeStamp'
        ],
        [
            'tag' => '63',
            'type' => 'crc',
            'required' => true,
            'instance' => 'KHQR\Models\CRC'
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
                'subTag' => EMV::BAKONG_ACCOUNT_IDENTIFIER,
                'name' => 'bakongAccountID',
            ],
            [
                'tag' => '29',
                'subTag' => EMV::MERCHANT_ACCOUNT_INFORMATION_MERCHANT_ID,
                'name' => 'accountInformation',
            ],
            [
                'tag' => '29',
                'subTag' => EMV::MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK,
                'name' => 'acquiringBank',
            ],
            [
                'tag' => '62',
                'subTag' => EMV::BILLNUMBER_TAG,
                'name' => 'billNumber',
            ],
            [
                'tag' => '62',
                'subTag' => EMV::ADDITIONAL_DATA_FIELD_MOBILE_NUMBER,
                'name' => 'mobileNumber',
            ],
            [
                'tag' => '62',
                'subTag' => EMV::STORELABEL_TAG,
                'name' => 'storeLabel',
            ],
            [
                'tag' => '62',
                'subTag' => EMV::PURPOSE_OF_TRANSACTION,
                'name' => 'purposeOfTransaction',
            ],
            [
                'tag' => '62',
                'subTag' => EMV::TERMINAL_TAG,
                'name' => 'terminalLabel',
            ],
            [
                'tag' => '64',
                'subTag' => EMV::LANGUAGE_PREFERENCE,
                'name' => 'languagePreference',
            ],
            [
                'tag' => '64',
                'subTag' => EMV::MERCHANT_NAME_ALTERNATE_LANGUAGE,
                'name' => 'merchantNameAlternateLanguage',
            ],
            [
                'tag' => '64',
                'subTag' => EMV::MERCHANT_CITY_ALTERNATE_LANGUAGE,
                'name' => 'merchantCityAlternateLanguage',
            ],
        ],
    ];
}
