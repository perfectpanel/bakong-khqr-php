<?php

declare(strict_types=1);

namespace KHQR\Models;

use KHQR\Helpers\KHQRData;
use KHQR\Exceptions\KHQRException;

class TransactionCurrency extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if ($value == "" || $value == null) {
			throw new KHQRException(KHQRException::CURRENCY_TYPE_REQUIRED);
		} else if (strlen($value) > 3) {
			throw new KHQRException(KHQRException::TRANSACTION_CURRENCY_LENGTH_INVALID);
		} else if (!in_array((int)$value, [KHQRData::CURRENCY_KHR, KHQRData::CURRENCY_USD])) {
			throw new KHQRException(KHQRException::UNSUPPORTED_CURRENCY);
		}
		parent::__construct($tag, $value);
	}
}
