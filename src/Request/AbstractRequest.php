<?php

declare(strict_types=1);

namespace App\Request;

class AbstractRequest
{
    public function validate(): bool
    {
        return true;
    }
}
