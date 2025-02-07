<?php

declare(strict_types=1);

namespace KHQR\Models;

use InvalidArgumentException;
use KHQR\Helpers\KHQRData;
use KHQR\Helpers\Utils;

class MerchantInfo
{
    // Required parameters
    public string $bakongAccountID;

    public string $merchantName;

    public string $merchantCity;

    public ?string $merchantID;

    // Optional parameters
    public ?string $acquiringBank;

    public ?string $accountInformation;

    public ?int $currency;

    public ?float $amount;

    public ?string $billNumber;

    public ?string $storeLabel;

    public ?string $terminalLabel;

    public ?string $mobileNumber;

    public ?string $purposeOfTransaction;

    public ?string $languagePreference;

    public ?string $merchantNameAlternateLanguage;

    public ?string $merchantCityAlternateLanguage;

    public ?string $upiMerchantAccount;

    public function __construct(
        string $bakongAccountID,
        string $merchantName,
        string $merchantCity,
        string $merchantID,
        string $acquiringBank,
        ?string $accountInformation = null,
        ?int $currency = null,
        float $amount = 0.0,
        ?string $billNumber = null,
        ?string $storeLabel = null,
        ?string $terminalLabel = null,
        ?string $mobileNumber = null,
        ?string $purposeOfTransaction = null,
        ?string $languagePreference = null,
        ?string $merchantNameAlternateLanguage = null,
        ?string $merchantCityAlternateLanguage = null,
        ?string $upiMerchantAccount = null
    ) {
        if (Utils::isBlank($bakongAccountID)) {
            throw new InvalidArgumentException('`bakongAccountID` cannot be blank');
        }

        if (Utils::isBlank($merchantName)) {
            throw new InvalidArgumentException('`merchantName` cannot be blank');
        }

        if (Utils::isBlank($merchantCity)) {
            throw new InvalidArgumentException('`merchantCity` cannot be blank');
        }

        if (Utils::isBlank($merchantID)) {
            throw new \InvalidArgumentException('`merchantID` cannot be blank');
        }

        if (Utils::isBlank($acquiringBank)) {
            throw new \InvalidArgumentException('`acquiringBank` cannot be blank');
        }

        $this->bakongAccountID = $bakongAccountID;
        $this->merchantName = $merchantName;
        $this->merchantCity = $merchantCity;
        $this->merchantID = $merchantID;
        // additional optional parameters
        $this->acquiringBank = $acquiringBank;
        $this->accountInformation = $accountInformation;
        $this->currency = $currency ?? KHQRData::CURRENCY_KHR;
        $this->amount = $amount;
        $this->billNumber = $billNumber;
        $this->storeLabel = $storeLabel;
        $this->terminalLabel = $terminalLabel;
        $this->mobileNumber = $mobileNumber;
        $this->purposeOfTransaction = $purposeOfTransaction;
        $this->languagePreference = $languagePreference;
        $this->merchantNameAlternateLanguage = $merchantNameAlternateLanguage;
        $this->merchantCityAlternateLanguage = $merchantCityAlternateLanguage;
        $this->upiMerchantAccount = $upiMerchantAccount;
    }
}
