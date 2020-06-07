<?php

declare(strict_types=1);

namespace App\Request\Api;

use App\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;

abstract class AbstractApiRequest extends AbstractRequest
{
    protected Request $request;

    protected array $content;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $this->getCurrentRequest($requestStack);

        try {
            $this->content = json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            $this->content = [];
        }
    }

    public function get(string $name, $default = null)
    {
        return $this->content[$name] ?? $default;
    }

    public function getInteger(string $name, int $default = null): ?int
    {
        return null !== $this->get($name) ? (int)$this->get($name) : $default;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getContentData(): array
    {
        return $this->content;
    }
}
