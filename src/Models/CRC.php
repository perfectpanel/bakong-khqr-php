<?php

use KHQR\TagLengthString;

class CRC extends TagLengthString
{
	public function __construct(string $tag, string $value)
	{
		if ($value == "" || $value == null) {
			throw new KHQRException(KHQRException::CRC_TAG_REQUIRED);
		} else if (strlen($value) > 4) {
			throw new KHQRException(KHQRException::CRC_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
