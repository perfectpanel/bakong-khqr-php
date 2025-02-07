<?php

declare(strict_types=1);

namespace KHQR\Models;

class TagLengthString
{
    private string $tag;

    private string $value;

    private string $length;

    public function __construct(string $tag, string $value)
    {
        $this->tag = $tag;
        $this->value = $value;

        $length = strlen($value);
        $this->length = $length < 10 ? '0'.$length : (string) $length;
    }

    public function __toString(): string
    {
        return $this->tag.$this->length.$this->value;
    }
}
