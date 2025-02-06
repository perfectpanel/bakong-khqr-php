<?php

use KHQR\TagLengthString;

class PointOfInitiationMethod extends TagLengthString
{
	public function __construct($tag, $value)
	{
		if (strlen($value) > 2) {
			throw new KHQRException(KHQRException::POINT_INITIATION_LENGTH_INVALID);
		}
		parent::__construct($tag, $value);
	}
}
