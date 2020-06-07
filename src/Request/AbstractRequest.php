<?php

declare(strict_types=1);

namespace App\Request;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractRequest implements RequestInterface
{
    protected string $error = '';

    /**
     * @return bool
     */
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

    protected function getCurrentRequest(RequestStack $requestStack): Request
    {
        $request = $requestStack->getCurrentRequest();
        if (null === $request) {
            throw new RuntimeException('request is null');
        }

        return $request;
    }
}
