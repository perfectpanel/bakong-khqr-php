<?php

require_once __DIR__ . '/KHQRException.php';

use KHQR\EMV;
use KHQR\TagLengthString;

class GlobalUniqueIdentifier extends TagLengthString
{
	public ?string $merchantID;
	public ?string $acquiringBank;
	public ?string $accountInformation;
	public string $bakongAccountID;

	public object $data;

	public function __construct($tag, $valueObject)
	{
		if ($valueObject == null) {
			throw new KHQRException(KHQRException::MERCHANT_TYPE_REQUIRED);
		}

		// Get value from props object
		$bakongAccountID = $valueObject->bakongAccountID;
		$acquiringBank = $valueObject->acquiringBank;

		$isMerchant = $valueObject->isMerchant;
		$accountInformation = $valueObject->accountInformation;

		// Creating 3 instances
		// BakongAccountID: 00
		// MerchantID: 01
		// AcquiringBankName: 02
		$bakongAccountId = new BakongAccountID(
			EMV::BAKONG_ACCOUNT_IDENTIFIER,
			$bakongAccountID
		);

		$globalUniqueIdentifier = $bakongAccountId;

		if ($isMerchant) {
			$merchantId = new MerchantId(
				EMV::MERCHANT_ACCOUNT_INFORMATION_MERCHANT_ID,
				$valueObject->merchantID
			);
			$acquiringBankName = new AcquiringBank(
				EMV::MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK,
				$acquiringBank
			);

			if ($valueObject->merchantID !== null) {
				$globalUniqueIdentifier .= $merchantId;
			}
			if ($acquiringBank !== null) {
				$globalUniqueIdentifier .= $acquiringBankName;
			}

			parent::__construct($tag, $globalUniqueIdentifier);

			$this->merchantID = $merchantId;
			$this->acquiringBank = $acquiringBankName;
			$this->data = (object) [
				'bakongAccountID' => $bakongAccountId,
				'merchantID' => $merchantId,
				'acquiringBank' => $acquiringBankName,
			];
		} else {
			if ($accountInformation !== null) {
				$accInformation = new AccountInformation(
					EMV::INDIVIDUAL_ACCOUNT_INFORMATION,
					$accountInformation
				);
				$globalUniqueIdentifier .= $accInformation;
			}

			if ($acquiringBank !== null) {
				$acquiringBankName = new AcquiringBank(
					EMV::MERCHANT_ACCOUNT_INFORMATION_ACQUIRING_BANK,
					$acquiringBank
				);
				$globalUniqueIdentifier .= $acquiringBankName;
			}

			parent::__construct($tag, $globalUniqueIdentifier);

			$this->accountInformation = $accountInformation;
			$this->data = (object) [
				'bakongAccountID' => $bakongAccountId,
				'accountInformation' => $accountInformation,
			];
		}
		$this->bakongAccountID = $bakongAccountId;
	}
}

class BakongAccountID extends TagLengthString
{
	public function __construct($tag, $bakongAccountID)
	{
		// Throw validation if there is
		// 1. No tag
		// 2. empty value of bakong account
		if (empty($bakongAccountID)) {
			throw new KHQRException(KHQRException::BAKONG_ACCOUNT_ID_REQUIRED);
		}

		// Validating the bakong account is it is correct
		// name@bank_domain
		$bakongAccountDivide = explode("@", $bakongAccountID);

		// Validate on length of the bakong account
		if (strlen($bakongAccountID) > EMV::INVALID_LENGTH_BAKONG_ACCOUNT) {
			throw new KHQRException(KHQRException::BAKONG_ACCOUNT_ID_LENGTH_INVALID);
		} else if (count($bakongAccountDivide) < 2) {
			throw new KHQRException(KHQRException::BAKONG_ACCOUNT_ID_INVALID);
		}
		parent::__construct($tag, $bakongAccountID);
	}
}

class AccountInformation extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_ACCOUNT_INFORMATION) {
			throw new KHQRException(KHQRException::ACCOUNT_INFORMATION_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}

class MerchantId extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (empty($value)) {
			throw new KHQRException(KHQRException::MERCHANT_ID_REQUIRED);
		} else if (strlen($value) > EMV::INVALID_LENGTH_MERCHANT_ID) {
			throw new KHQRException(KHQRException::MERCHANT_ID_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}

class AcquiringBank extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (empty($value)) {
			throw new KHQRException(KHQRException::ACQUIRING_BANK_REQUIRED);
		} else if (strlen($value) > EMV::INVALID_LENGTH_ACQUIRING_BANK) {
			throw new KHQRException(KHQRException::ACQUIRING_BANK_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
