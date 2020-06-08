<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Exception\Common\AdminException;
use Rollbar\Rollbar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
    public function getCurrentUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $exception = AdminException::userIsInvalid();
            Rollbar::error($exception);
            throw $exception;
        }

        return $user;
    }
}
