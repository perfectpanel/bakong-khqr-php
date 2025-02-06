<?php

use KHQR\EMV;
use KHQR\TagLengthString;

class CountryCode extends TagLengthString
{
	public function __construct(string $tag, string $value)
	{
		if ($value == "" || $value == null) {
			throw new KHQRException(KHQRException::COUNTRY_CODE_TAG_REQUIRED);
		} else if (strlen($value) > EMV::INVALID_LENGTH_COUNTRY_CODE) {
			throw new KHQRException(KHQRException::COUNTRY_CODE_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
