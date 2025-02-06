<?php

require_once __DIR__ . '/Constants.php';
require_once __DIR__ . '/TagLengthString.php';

use KHQR\EMV;
use KHQR\TagLengthString;

class AdditionalData extends TagLengthString
{
	public string $billNumber;
	public string $mobileNumber;
	public string $storeLabel;
	public string $terminalLabel;

	public object $data;

	public function __construct($tag, $additionalData = null)
	{
		if ($additionalData == null) {
			$additionalData = (object) [
				'billNumberInput' => null,
				'mobileNumberInput' => null,
				'storeLabelInput' => null,
				'terminalLabelInput' => null,
			];
		}

		// Getting information from additionalData
		$billNumberInput = $additionalData->billNumber;
		$mobileNumberInput = $additionalData->mobileNumber;
		$storeLabelInput = $additionalData->storeLabel;
		$terminalLabelInput = $additionalData->terminalLabel;
		$purposeOfTransaction = $additionalData->purposeOfTransaction;

		$billNumber = null;
		$mobileNumber = null;
		$storeLabel = null;
		$terminalLabel = null;

		// Create additional data tag by combine all three sub tags
		$additionalDataString = "";
		if ($billNumberInput !== null) {
			$billNumber = new BillNumber(EMV::BILLNUMBER_TAG, $billNumberInput);
			$additionalDataString .= $billNumber;
		}
		if ($mobileNumberInput !== null) {
			$mobileNumber = new MobileNumber(
				EMV::ADDITIONAL_DATA_FIELD_MOBILE_NUMBER,
				$mobileNumberInput
			);
			$additionalDataString .= $mobileNumber;
		}
		if ($storeLabelInput !== null) {
			$storeLabel = new StoreLabel(EMV::STORELABEL_TAG, $storeLabelInput);
			$additionalDataString .= $storeLabel;
		}
		if ($terminalLabelInput !== null) {
			$terminalLabel = new TerminalLabel(
				EMV::TERMINAL_TAG,
				$terminalLabelInput
			);
			$additionalDataString .= $terminalLabel;
		}

		if ($purposeOfTransaction !== null) {
			$purpose = new PurposeOfTransaction(EMV::PURPOSE_OF_TRANSACTION, $purposeOfTransaction);
			$additionalDataString .= $purpose;
		}

		parent::__construct($tag, $additionalDataString);

		// class inherit the billNumber, storeLabel, terminalLabel
		$this->billNumber = $billNumber;
		$this->mobileNumber = $mobileNumber;
		$this->storeLabel = $storeLabel;
		$this->terminalLabel = $terminalLabel;
		$this->data = (object) [
			'billNumber' => $billNumber,
			'mobileNumber' => $mobileNumber,
			'storeLabel' => $storeLabel,
			'terminalLabel' => $terminalLabel,
			'purposeOfTransaction' => $purposeOfTransaction
		];
	}
}

class BillNumber extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_BILL_NUMBER || $value == "") {
			throw new KHQRException(KHQRException::BILL_NUMBER_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}

class StoreLabel extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_STORE_LABEL || $value == "") {
			throw new KHQRException(KHQRException::STORE_LABEL_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}

class TerminalLabel extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_TERMINAL_LABEL || $value == "") {
			throw new KHQRException(KHQRException::TERMINAL_LABEL_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}

class MobileNumber extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_MOBILE_NUMBER || $value == "") {
			throw new KHQRException(KHQRException::MOBILE_NUMBER_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}

class PurposeOfTransaction extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_PURPOSE_OF_TRANSACTION || $value == "") {
			throw new KHQRException(KHQRException::PURPOSE_OF_TRANSACTION_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
