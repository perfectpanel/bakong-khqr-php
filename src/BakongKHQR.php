<?php

declare(strict_types=1);

namespace KHQR;

use Exception;
use KHQR\Api\DeepLink;
use KHQR\Exceptions\KHQRException;
use KHQR\Helpers\EMV;
use KHQR\Helpers\KHQRData;
use KHQR\Helpers\Utils;
use KHQR\Models\AdditionalData;
use KHQR\Models\CountryCode;
use KHQR\Models\CRCValidation;
use KHQR\Models\GlobalUniqueIdentifier;
use KHQR\Models\IndividualInfo;
use KHQR\Models\KHQRDeepLinkData;
use KHQR\Models\KHQRResponse;
use KHQR\Models\MerchantCategoryCode;
use KHQR\Models\MerchantCity;
use KHQR\Models\MerchantInfo;
use KHQR\Models\MerchantInformationLanguageTemplate;
use KHQR\Models\MerchantName;
use KHQR\Models\PayloadFormatIndicator;
use KHQR\Models\PointOfInitiationMethod;
use KHQR\Models\SourceInfo;
use KHQR\Models\TimeStamp;
use KHQR\Models\TransactionAmount;
use KHQR\Models\TransactionCurrency;
use KHQR\Models\UnionpayMerchantAccount;

class BakongKHQR
{
    public static function generateIndividual(IndividualInfo $individualInfo): \KHQR\Models\KHQRResponse
    {
        $khqr = self::generateKHQR($individualInfo, KHQRData::MERCHANT_TYPE_INDIVIDUAL);
        $result = [
            'qr' => $khqr,
            'md5' => md5($khqr),
        ];

        return new KHQRResponse($result, null);
    }

    public static function generateMerchant(MerchantInfo $merchantInfo): \KHQR\Models\KHQRResponse
    {
        $khqr = self::generateKHQR($merchantInfo, KHQRData::MERCHANT_TYPE_MERCHANT);
        $result = [
            'qr' => $khqr,
            'md5' => md5($khqr),
        ];

        return new KHQRResponse($result, null);
    }

    public static function decode(string $khqrString): \KHQR\Models\KHQRResponse
    {
        $decodedData = self::decodeKHQRString($khqrString);

        return new KHQRResponse($decodedData, null);
    }

    public static function verify(string $KHQRString): CRCValidation
    {
        $isCorrectFormCRC = Utils::checkCRCRegExp($KHQRString);
        if (! $isCorrectFormCRC) {
            return new CRCValidation(false);
        }

        $crc = substr($KHQRString, -4);
        $KHQRNoCrc = substr($KHQRString, 0, -4);
        $validCRC = Utils::crc16($KHQRNoCrc) == strtoupper($crc);
        $isValidCRC = new CRCValidation($validCRC);

        try {
            if (! $isValidCRC->isValid || strlen($KHQRString) < EMV::INVALID_LENGTH_KHQR) {
                throw new KHQRException(KHQRException::KHQR_INVALID);
            }

            self::decodeKHQRValidation($KHQRString);

            return new CRCValidation(true);
        } catch (Exception $error) {
            error_log($error->getMessage());
            error_log($error->getTraceAsString());
            return new CRCValidation(false);
        }
    }

    public static function generateDeepLink(string $url, string $qr, ?SourceInfo $sourceInfo): KHQRResponse
    {
        // Check if URL is valid
        if (!DeepLink::isValidLink($url)) {
            throw new KHQRException(KHQRException::INVALID_DEEP_LINK_URL);
        }

        // Validate QR (CRC check)
        $isValidKHQR = self::verify($qr);
        if (! $isValidKHQR->isValid) {
            throw new KHQRException(KHQRException::KHQR_INVALID);
        }

        // Validate sourceInfo fields if provided
        if ($sourceInfo && ($sourceInfo->appIconUrl === null || $sourceInfo->appIconUrl === '' || $sourceInfo->appIconUrl === '0' ||
            ($sourceInfo->appName === null || $sourceInfo->appName === '' || $sourceInfo->appName === '0') ||
            ($sourceInfo->appDeepLinkCallback === null || $sourceInfo->appDeepLinkCallback === '' || $sourceInfo->appDeepLinkCallback === '0'))) {
            throw new KHQRException(KHQRException::INVALID_DEEP_LINK_SOURCE_INFO);
        }
        // Call API to generate deep link
        $data = DeepLink::callDeepLinkAPI($url, ['qr' => $qr]);
        $deepLinkData = new KHQRDeepLinkData($data['data']['shortLink']);

        return new KHQRResponse($deepLinkData, null);
    }

    public static function checkBakongAccount(string $url, string $bakongID): KHQRResponse
    {
        $accountExistResponse = Utils::checkBakongAccountExistence($url, $bakongID);

        return new KHQRResponse($accountExistResponse, null);
    }

    /**
     * Decode helper function
     * This decode funcition has a flow of
     * 1. Slice the string as each KHQR tag and store into memory
     * 2. Check if the required field exist
     * 3. Check if the KHQR Code given is in order or not
     * 4. Get the value of each tag and if there is subtag repeat number 1
     *
     * @param  string  $khqrString  The KHQR string to decode.
     * @return array An associative array containing the decoded KHQR string.
     */
    private static function decodeKHQRValidation(string $khqrString): array
    {
        $allField = array_map(fn($el): string => $el['tag'], KHQRData::KHQRTag);
        $subtag = array_map(fn($obj): string => $obj['tag'], array_filter(KHQRData::KHQRTag, fn($el): bool => isset($el['sub']) && $el['sub']));
        $requiredField = array_map(fn($el): string => $el['tag'], array_filter(KHQRData::KHQRTag, fn($el): bool => $el['required'] == true));
        $subTagInput = KHQRData::KHQRSubtag['input'];
        $subTagCompare = KHQRData::KHQRSubtag['compare'];

        $tags = [];
        $merchantType = 'individual';
        $lastTag = '';

        while ($khqrString) {
            $sliceTagObject = Utils::cutString($khqrString);
            $tag = $sliceTagObject['tag'];
            $value = $sliceTagObject['value'];
            $slicedString = $sliceTagObject['slicedString'];

            if ($tag == $lastTag) {
                break;
            }

            $isMerchant = $tag == '30';

            if ($isMerchant) {
                $merchantType = 'merchant';
                $tag = '29';
            }

            if (in_array($tag, $allField)) {
                $tags[] = ['tag' => $tag, 'value' => $value];
                $requiredField = array_filter($requiredField, fn($el): bool => $el != $tag);
            }

            $khqrString = $slicedString;
            $lastTag = $tag;
        }

        $requiredFieldNotExist = count($requiredField) != 0;
        if ($requiredFieldNotExist) {
            $requiredTag = $requiredField[0];
            $missingInstance = Utils::findTag(KHQRData::KHQRTag, $requiredTag)['instance'];
            new $missingInstance($requiredTag, null);
        }

        $decodeValue = [
            'merchantType' => $merchantType,
        ];

        foreach (
            array_map(fn($el): array => $el['data'], $subTagInput) as $obj
        ) {
            $decodeValue = array_merge($decodeValue, $obj);
        }

        foreach ($tags as $khqrTag) {
            $tag = $khqrTag['tag'];
            $khqr = current(array_filter(KHQRData::KHQRTag, fn($el): bool => $el['tag'] == $tag));
            $value = $khqrTag['value'];
            $inputValue = $value;

            if (in_array($tag, $subtag)) {
                $inputdata = Utils::findTag($subTagInput, $tag)['data'];
                while ($value) {
                    $cutsubstring = Utils::cutString($value);
                    $tempSubtag = $cutsubstring['tag'];
                    $subtagValue = $cutsubstring['value'];
                    $slicedSubtag = $cutsubstring['slicedString'];

                    $nameSubtag = array_filter($subTagCompare, fn($el): bool => $el['tag'] == $tag);
                    $nameSubtag = array_filter($nameSubtag, fn($el): bool => $el['subTag'] == $tempSubtag)[0];

                    if ($nameSubtag != null) {
                        $nameSubtag = $nameSubtag['name'];
                        $inputdata[$nameSubtag] = $subtagValue;
                        $inputValue = $inputdata;
                    }
                    $value = $slicedSubtag;
                }
                $decodeValue = array_merge($decodeValue, $inputValue);
            } else {
                $instance = new $khqr['instance']($tag, $inputValue);
                $decodeValue[$khqr['type']] = $instance->value;
            }
        }

        return $decodeValue;
    }

    /**
     * Decode helper function
     * This decode funcition has a flow of
     * 1. Slice the string as each KHQR tag and store into memory
     * 2. Check if the required field exist
     * 3. Check if the KHQR Code given is in order or not
     * 4. Get the value of each tag and if there is subtag repeat number 1
     *
     * @param  string  $khqrString  The KHQR string to decode.
     * @return array An associative array containing the decoded KHQR string.
     */
    private static function decodeKHQRString(string $khqrString): array
    {
        $allField = array_map(fn($el): string => $el['tag'], KHQRData::KHQRTag);
        $subtag = array_map(fn($obj): string => $obj['tag'], array_filter(KHQRData::KHQRTag, fn($el): bool => isset($el['sub']) && $el['sub']));
        $requiredField = array_map(fn($el): string => $el['tag'], array_filter(KHQRData::KHQRTag, fn($el): bool => isset($el['required']) && $el['required'] == true));

        $subTagInput = KHQRData::KHQRSubtag['input'];
        $subTagCompare = KHQRData::KHQRSubtag['compare'];

        $tags = [];
        $merchantType = null;
        $lastTag = '';
        $isMerchantTag = false;

        while (strlen($khqrString) > 0) {
            $sliceTagObject = Utils::cutString($khqrString);
            $tag = $sliceTagObject['tag'];
            $value = $sliceTagObject['value'];
            $slicedString = $sliceTagObject['slicedString'];

            if ($tag == $lastTag) {
                break;
            }

            $isMerchant = $tag == '30';
            if ($isMerchant) {
                $merchantType = '30';
                $tag = '29';
                $isMerchantTag = true;
            } elseif ($tag == '29') {
                $merchantType = '29';
            }

            if (in_array($tag, $allField)) {
                $tags[$tag] = $value;
                $requiredField = array_filter($requiredField, fn($el): bool => $el != $tag);
            }

            $khqrString = $slicedString;
            $lastTag = $tag;
        }

        $decodeValue = ['merchantType' => $merchantType];

        foreach ($subTagInput as $el) {
            $decodeValue = array_merge($decodeValue, $el['data']);
        }

        foreach (KHQRData::KHQRTag as $khqrTag) {
            $tag = $khqrTag['tag'];
            $khqr = current(array_filter(KHQRData::KHQRTag, fn($el): bool => $el['tag'] === $tag));
            $value = $tags[$tag] ?? null;
            $inputValue = $value;

            if (in_array($tag, $subtag)) {
                $inputdata = Utils::findTag($subTagInput, $tag)['data'];
                while ($value) {
                    $cutsubstring = Utils::cutString($value);
                    $tempSubtag = $cutsubstring['tag'];
                    $subtagValue = $cutsubstring['value'];
                    $slicedSubtag = $cutsubstring['slicedString'];

                    $nameSubtag = current(array_filter($subTagCompare, fn($el): bool => $el['tag'] === $tag && $el['subTag'] == $tempSubtag));

                    if ($nameSubtag) {
                        $nameSubtag = $nameSubtag['name'];
                        if ($isMerchantTag && $nameSubtag == 'accountInformation') {
                            $nameSubtag = 'merchantID';
                        }
                        $inputdata[$nameSubtag] = $subtagValue;
                        $inputValue = $inputdata;
                    }
                    $value = $slicedSubtag;
                }

                if (!is_null($inputValue)) {
                    $decodeValue = array_merge($decodeValue, $inputValue);
                }
            } else {
                $decodeValue[$khqr['type']] = $value;
                if ($tag === '99' && $value == null) {
                    $decodeValue[$khqr['type']] = null;
                }
            }
        }

        return $decodeValue;
    }

    private static function generateKHQR(MerchantInfo $information, string $type)
    {
        if ($type === KHQRData::MERCHANT_TYPE_MERCHANT) {
            $merchantInfo = (object) [
                'bakongAccountID' => $information->bakongAccountID,
                'merchantID' => $information->merchantID,
                'acquiringBank' => $information->acquiringBank,
                'isMerchant' => true,
            ];
        } else {
            $merchantInfo = (object) [
                'bakongAccountID' => $information->bakongAccountID,
                'accountInformation' => $information->accountInformation,
                'acquiringBank' => $information->acquiringBank,
                'isMerchant' => false,
            ];
        }

        $additionalDataInformation = [
            'billNumber' => $information->billNumber,
            'mobileNumber' => $information->mobileNumber,
            'storeLabel' => $information->storeLabel,
            'terminalLabel' => $information->terminalLabel,
            'purposeOfTransaction' => $information->purposeOfTransaction,
        ];

        $languageInformation = [
            'languagePreference' => $information->languagePreference,
            'merchantNameAlternateLanguage' => $information->merchantNameAlternateLanguage,
            'merchantCityAlternateLanguage' => $information->merchantCityAlternateLanguage,
        ];

        try {
            $amount = $information->amount;
            $payloadFormatIndicator = new PayloadFormatIndicator(EMV::PAYLOAD_FORMAT_INDICATOR, EMV::DEFAULT_PAYLOAD_FORMAT_INDICATOR);
            $QRType = EMV::DYNAMIC_QR;

            if (! isset($amount) || $amount == 0) {
                $QRType = EMV::STATIC_QR;
            }

            $pointOfInitiationMethod = new PointOfInitiationMethod(EMV::POINT_OF_INITIATION_METHOD, $QRType);

            $upi = null;
            if ($information->upiMerchantAccount !== null && $information->upiMerchantAccount !== '' && $information->upiMerchantAccount !== '0') {
                $upi = new UnionpayMerchantAccount(EMV::UNIONPAY_MERCHANT_ACCOUNT, $information->upiMerchantAccount);
            }

            $KHQRType = ($type === KHQRData::MERCHANT_TYPE_MERCHANT) ? EMV::MERCHANT_ACCOUNT_INFORMATION_MERCHANT : EMV::MERCHANT_ACCOUNT_INFORMATION_INDIVIDUAL;
            $globalUniqueIdentifier = new GlobalUniqueIdentifier($KHQRType, $merchantInfo);
            $merchantCategoryCode = new MerchantCategoryCode(EMV::MERCHANT_CATEGORY_CODE, EMV::DEFAULT_MERCHANT_CATEGORY_CODE);
            $currency = new TransactionCurrency(EMV::TRANSACTION_CURRENCY, $information->currency);

            if ($information->currency == KHQRData::CURRENCY_USD && $upi) {
                throw new KHQRException(KHQRException::UPI_ACCOUNT_INFORMATION_INVALID_CURRENCY);
            }

            $KHQRInstances = [
                $payloadFormatIndicator,
                $pointOfInitiationMethod,
                $upi ?: '',
                $globalUniqueIdentifier,
                $merchantCategoryCode,
                $currency,
            ];

            if (isset($amount) && $amount != 0) {
                $amountInput = $information->amount;
                if ($information->currency == KHQRData::CURRENCY_KHR) {
                    if (floor($amountInput) == $amountInput) {
                        $amountInput = round($amountInput);
                    } else {
                        throw new KHQRException(KHQRException::TRANSACTION_AMOUNT_INVALID);
                    }
                } else {
                    $amountSplit = explode('.', (string) $amountInput);
                    if (isset($amountSplit[1]) && strlen($amountSplit[1]) > 2) {
                        throw new KHQRException(KHQRException::TRANSACTION_AMOUNT_INVALID);
                    }
                    $amountInput = number_format($amountInput, 2, '.', '');
                }
                $KHQRInstances[] = new TransactionAmount(EMV::TRANSACTION_AMOUNT, $amountInput);
            }

            $countryCode = new CountryCode(EMV::COUNTRY_CODE, EMV::DEFAULT_COUNTRY_CODE);
            $KHQRInstances[] = $countryCode;

            $merchantName = new MerchantName(EMV::MERCHANT_NAME, $information->merchantName);
            $KHQRInstances[] = $merchantName;

            $merchantCity = new MerchantCity(EMV::MERCHANT_CITY, $information->merchantCity);
            $KHQRInstances[] = $merchantCity;

            if (array_filter($additionalDataInformation) !== []) {
                $additionalData = new AdditionalData(EMV::ADDITIONAL_DATA_TAG, $additionalDataInformation);
                $KHQRInstances[] = $additionalData;
            }

            if (array_filter($languageInformation) !== []) {
                $languageTemplate = new MerchantInformationLanguageTemplate(EMV::MERCHANT_INFORMATION_LANGUAGE_TEMPLATE, $languageInformation);
                $KHQRInstances[] = $languageTemplate;
            }

            $timeStamp = new TimeStamp(EMV::TIMESTAMP_TAG);
            $KHQRInstances[] = $timeStamp;

            $khqrNoCrc = '';
            foreach ($KHQRInstances as $instance) {
                $khqrNoCrc .= (string) $instance;
            }

            $khqr = $khqrNoCrc . EMV::CRC . EMV::CRC_LENGTH;

            return $khqr . Utils::crc16($khqr);
        } catch (Exception $error) {
            return $error;
        }
    }
}
