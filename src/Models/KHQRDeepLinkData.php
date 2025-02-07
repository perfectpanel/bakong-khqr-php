<?php

declare(strict_types=1);

namespace KHQR\Models;

class KHQRDeepLinkData
{
    private string $shortLink;

    public function __construct(string $shortLink)
    {
        $this->shortLink = $shortLink;
    }

    public function getData(): array
    {
        return [
            'shortLink' => $this->shortLink,
        ];
    }
}
