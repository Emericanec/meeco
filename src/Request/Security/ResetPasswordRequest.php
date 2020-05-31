<?php

declare(strict_types=1);


namespace App\Request\Security;

use App\Entity\User;
use App\Exception\Common\RequestValidationException;
use App\Repository\UserRepository;
use App\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Exception\ValidatorException;

class ResetPasswordRequest extends AbstractRequest
{
    private const PARAM_EMAIL = 'email';

    private ?string $email;

    private Request $request;

    private UserRepository $userRepository;

    private ?User $user;

    public function __construct(RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->request = $this->getCurrentRequest($requestStack);
        $this->userRepository = $userRepository;
        $this->email = $this->request->get(self::PARAM_EMAIL);
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

        $this->user = $this->userRepository->findOneByEmail($this->email);
        if (null === $this->user) {
            $this->setError('User with this email does not exist');
            throw new RequestValidationException($this->getError());
        }

        return true;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
