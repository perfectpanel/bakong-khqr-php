<?php

use KHQR\EMV;
use KHQR\TagLengthString;

class UnionpayMerchantAccount extends TagLengthString
{
	public function __construct(string $tag, string $value)
	{
		if (strlen($value) > EMV::INVALID_LENGTH_UPI_MERCHANT) {
			throw new KHQRException(KHQRException::UPI_ACCOUNT_INFORMATION_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
