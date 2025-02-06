<?php

require_once __DIR__ . '/TagLengthString.php';

use KHQR\TagLengthString;

class PayloadFormatIndicator extends TagLengthString
{
	public function __construct(string $tag, string $value)
	{
		if ($value === "" || $value === null) {
			throw new KHQRException(KHQRException::PAYLOAD_FORMAT_INDICATOR_TAG_REQUIRED);
		} elseif (strlen($value) > 2) {
			throw new KHQRException(KHQRException::PAYLOAD_FORMAT_INDICATOR_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
