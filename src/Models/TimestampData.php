<?php

declare(strict_types=1);

namespace KHQR\Models;

class TimestampData
{
    public ?int $creationTimestamp;

    public ?int $expirationTimestamp;

    public function __construct(?int $creationTimestamp, ?int $expirationTimestamp)
    {
        $this->creationTimestamp = $creationTimestamp;
        $this->expirationTimestamp = $expirationTimestamp;
    }
}
