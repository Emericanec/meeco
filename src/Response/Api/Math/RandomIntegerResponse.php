<?php

declare(strict_types=1);


namespace App\Response\Api\Math;

use App\Response\AbstractJsonResponse;

class RandomIntegerResponse extends AbstractJsonResponse
{
    private int $result;

    public function __construct(int $result)
    {
        $this->result = $result;
    }

    public function toArray(): array
    {
        return [
            'result' => $this->result
        ];
    }
}
