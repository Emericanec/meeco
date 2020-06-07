<?php

declare(strict_types=1);

namespace App\Request\Integration\Google;

use App\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CodeRequest extends AbstractRequest
{
    private Request $request;

    private ?string $code = null;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->code = $this->request->get('code', null);
    }

    public function validate(): bool
    {
        return !(null === $this->code);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
