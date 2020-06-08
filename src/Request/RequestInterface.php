<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public function getRequest(): Request;

    public function getError(): string;

    public function setError(string $error): void;

    public function validate(): bool;
}
