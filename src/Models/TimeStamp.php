<?php

declare(strict_types=1);

namespace KHQR\Models;

class TimeStamp extends TagLengthString
{
	public function __construct($tag)
	{
		$milisecondTimeStamp = floor(microtime(true) * 1000);
		$timeStamp = new TimeStampMiliSecond("00", $milisecondTimeStamp);
		$value = (string) $timeStamp;
		parent::__construct($tag, $value);
	}
}

class TimeStampMiliSecond extends TagLengthString
{
	public function __construct($tag, $value)
	{
		parent::__construct($tag, $value);
	}
}
