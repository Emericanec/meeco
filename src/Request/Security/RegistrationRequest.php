<?php

declare(strict_types=1);

namespace App\Request\Security;

use App\Exception\Common\RequestValidationException;
use App\Repository\UserRepository;
use App\Request\AbstractRequest;
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
        $this->request = $this->getCurrentRequest($requestStack);
        $this->userRepository = $userRepository;
        $this->email = $this->request->get(self::PARAM_EMAIL);
        $this->password = $this->request->get(self::PARAM_PASSWORD);
    }

    /**
     * @return bool
     * @throws RequestValidationException
     */
    public function validate(): bool
    {
        if (empty($this->email)) {
            $this->setError('Email can not be empty');
            throw new RequestValidationException($this->getError());
        }

        if (empty($this->password)) {
            $this->setError('Password can not be empty');
            throw new RequestValidationException($this->getError());
        }

        $user = $this->userRepository->findOneByEmail($this->email);
        if (null !== $user) {
            $this->setError('Email already exist');
            throw new RequestValidationException($this->getError());
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
