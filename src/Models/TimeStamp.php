<?php

declare(strict_types=1);

namespace KHQR\Models;

class TimeStamp extends TagLengthString
{
    public function __construct(string $tag)
    {
        $milisecondTimeStamp = floor(microtime(true) * 1000);
        $timeStamp = new TimeStampMiliSecond('00', (string) $milisecondTimeStamp);
        $value = (string) $timeStamp;
        parent::__construct($tag, $value);
    }
}

class TimeStampMiliSecond extends TagLengthString
{
    public function __construct(string $tag, string $value)
    {
        parent::__construct($tag, $value);
    }
}
