<?php

declare(strict_types=1);

namespace App\Model\Integration;

use App\Entity\Integration;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface IntegrationInterface
{
    public function getTokenStorage(): TokenStorageInterface;

    public function getIntegration(): ?Integration;

    public function isConnected(): bool;

    public function getAuthUrl(): ?string;

    public function getCurrentUser(): User;
}
