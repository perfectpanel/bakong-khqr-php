<?php

require_once __DIR__ . '/Utils.php';
require_once __DIR__ . '/MerchantInfo.php';

use KHQR\KHQRData;
use KHQR\Utils;

class IndividualInfo extends MerchantInfo
{
	// add additional optional parameters to the constructor
	public function __construct(
		string $bakongAccountID,
		string $merchantName,
		string $merchantCity,
		string $acquiringBank = null,
		string $accountInformation = null,
		string $currency = null,
		float $amount = 0.0,
		string $billNumber = null,
		string $storeLabel = null,
		string $terminalLabel = null,
		string $mobileNumber = null,
		string $purposeOfTransaction = null,
		string $languagePreference = null,
		string $merchantNameAlternateLanguage = null,
		string $merchantCityAlternateLanguage = null,
		string $upiMerchantAccount = null
	) {
		if (Utils::isBlank($bakongAccountID)) {
			throw new InvalidArgumentException("`bakongAccountID` cannot be blank");
		}

		if (Utils::isBlank($merchantName)) {
			throw new InvalidArgumentException("`merchantName` cannot be blank");
		}

		if (Utils::isBlank($merchantCity)) {
			throw new InvalidArgumentException("`merchantCity` cannot be blank");
		}

		$this->bakongAccountID = $bakongAccountID;
		$this->merchantName = $merchantName;
		$this->merchantCity = $merchantCity;
		// additional optional parameters
		$this->acquiringBank = $acquiringBank;
		$this->accountInformation = $accountInformation;
		$this->currency = isset($currency) ? $currency : KHQRData::CURRENCY_KHR;
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
