<?php

use KHQR\EMV;
use KHQR\TagLengthString;

class MerchantName extends TagLengthString
{
	public function __construct(string $tag, string $value)
	{
		if ($value == "" || $value == null) {
			throw new KHQRException(KHQRException::MERCHANT_NAME_REQUIRED);
		} else if (strlen($value) > EMV::INVALID_LENGTH_MERCHANT_NAME) {
			throw new KHQRException(KHQRException::MERCHANT_NAME_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
