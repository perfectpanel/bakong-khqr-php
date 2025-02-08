<?php

declare(strict_types=1);

namespace KHQR\Models;

class KHQRResponse
{
    public object $status;

    public $data;

    public function __construct($data, ?array $errorObject)
    {
        $this->data = $data;

        $isError = $errorObject === null;
        $this->status = (object) [
            'code' => $isError ? 0 : 1,
            'errorCode' => $isError ? null : $errorObject['code'],
            'message' => $isError ? null : $errorObject['message'],
        ];
    }

    public function __toString(): string
    {
        return json_encode([
            'status' => $this->status,
            'data' => $this->data,
        ]);
    }
}
