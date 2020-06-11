<?php

declare(strict_types=1);


namespace App\Model\Integration;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class AbstractIntegration implements IntegrationInterface
{
    public function getCurrentUser(): User
    {
        $token = $this->getTokenStorage()->getToken();
        if (null === $token) {
            throw new UnauthorizedHttpException('User not found');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('User not found');
        }

        return $user;
    }
}
