<?php

declare(strict_types=1);

namespace App\Request;

class AbstractRequest
{
    protected string $error = '';

    public function validate(): bool
    {
        return true;
    }

    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
