<?php

declare(strict_types=1);

namespace App\Request\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\AbstractRequest;
use InvalidArgumentException;
use Rollbar\Rollbar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;

abstract class AbstractApiRequest extends AbstractRequest
{
    protected Request $request;

    protected array $content;

    protected string $apiToken;

    private User $user;

    public function __construct(RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->request = $this->getCurrentRequest($requestStack);

        try {
            $content = (string)$this->request->getContent();
            $this->content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            $this->content = [];
        }

        $this->apiToken = (string)$this->getRequest()->get('api_token', '');

        $user = $userRepository->findOneByApiToken($this->getApiToken());
        if (null === $user) {
            $exception = new InvalidArgumentException('Can not find user by api token');
            Rollbar::error($exception, [
                'method' => $this->getRequest()->getUri(),
                'apiToken' => $this->getApiToken(),
            ]);

            throw $exception;
        }

        $this->user = $user;
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        return $this->content[$name] ?? $default;
    }

    public function getInteger(string $name, int $default = null): ?int
    {
        return null !== $this->get($name) ? (int)$this->get($name) : $default;
    }

    public function getString(string $name, string $default = null): ?string
    {
        return null !== $this->get($name) ? (string)$this->get($name) : $default;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getContentData(): array
    {
        return $this->content;
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
