<?php

use KHQR\EMV;
use KHQR\TagLengthString;

class MerchantCategoryCode extends TagLengthString
{
	public function __construct(string $tag, string $value)
	{
		if ($value == "" || $value == null) {
			throw new KHQRException(KHQRException::MERCHANT_CATEGORY_TAG_REQUIRED);
		} else if (strlen($value) > EMV::INVALID_LENGTH_MERCHANT_CATEGORY_CODE) {
			throw new KHQRException(KHQRException::MERCHANT_CODE_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
