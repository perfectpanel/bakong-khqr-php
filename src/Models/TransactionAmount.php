<?php

declare(strict_types=1);

namespace KHQR\Models;

use KHQR\Helpers\EMV;
use KHQR\Exceptions\KHQRException;

class TransactionAmount extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (
			strlen((string)$value) > EMV::INVALID_LENGTH_AMOUNT ||
			strpos((string)$value, '-') !== false ||
			$value == "" ||
			$value == null
		) {
			throw new KHQRException(KHQRException::TRANSACTION_AMOUNT_INVALID);
		}

		parent::__construct($tag, $value);
	}
}
