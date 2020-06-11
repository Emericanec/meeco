<?php

declare(strict_types=1);

namespace App\Model\Integration;

use App\Entity\Integration;
use App\Repository\IntegrationRepository;
use App\Service\Google\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GoogleServiceIntegration extends AbstractIntegration
{
    private Client $client;

    private IntegrationRepository $repository;

    private TokenStorageInterface $tokenStorage;

    private ?Integration $integration = null;

    public function __construct(Client $client, IntegrationRepository $repository, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->tokenStorage = $tokenStorage;
    }

    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    public function getIntegration(): ?Integration
    {
        if (null === $this->integration) {
            $this->integration = $this->repository->findOneByType($this->getCurrentUser(), Integration::TYPE_GOOGLE_SERVICE);
        }

        return $this->integration;
    }

    public function isConnected(): bool
    {
        return null !== $this->getIntegration();
    }

    public function getAuthUrl(): ?string
    {
        return (string)$this->client->getClient()->createAuthUrl();
    }
}
