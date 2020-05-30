<?php

declare(strict_types=1);

namespace App\Request\Security;

use App\Repository\UserRepository;
use App\Request\AbstractRequest;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RegistrationRequest extends AbstractRequest
{
    private const PARAM_EMAIL = 'email';
    private const PARAM_PASSWORD = 'password';

    private ?string $email;

    private ?string $password;

    private UserRepository $userRepository;

    private Request $request;

    public function __construct(RequestStack $requestStack, UserRepository $userRepository)
    {
        $request = $requestStack->getCurrentRequest();
        if (null === $request) {
            throw new RuntimeException('request is null');
        }

        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->email = $this->request->get(self::PARAM_EMAIL);
        $this->password = $this->request->get(self::PARAM_PASSWORD);
    }

    public function validate(): bool
    {
        if (empty($this->email)) {
            return false;
        }

        $user = $this->userRepository->findOneByEmail($this->email);
        if (null !== $user) {
            return false;
        }

        if (empty($this->password))
        {
            return false;
        }

        return true;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
