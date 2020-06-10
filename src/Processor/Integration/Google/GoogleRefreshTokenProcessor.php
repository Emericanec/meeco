<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use App\Entity\User;
use App\Exception\Common\IntegrationException;
use App\Repository\IntegrationRepository;
use App\Service\Google\Client;
use Exception;
use Rollbar\Rollbar;

class GoogleRefreshTokenProcessor
{
    private Client $client;

    private IntegrationRepository $repository;

    private GoogleSaveTokenProcessor $processor;

    public function __construct(Client $client, IntegrationRepository $repository, GoogleSaveTokenProcessor $processor)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->processor = $processor;
    }

    public function refresh(User $user): Integration
    {
        $integration = $this->repository->findOneByType($user, Integration::TYPE_GOOGLE_SERVICE);
        if (null === $integration) {
            $exception = IntegrationException::notFoundException();

            Rollbar::error($exception, [
                'userId' => $user->getId(),
                'integrationType' => Integration::TYPE_GOOGLE_SERVICE
            ]);

            throw $exception;
        }

        $token = $this->client->getClient()->refreshToken($integration->getRefreshToken());

        try {
            return $this->processor->process($user, $token['access_token'], $token['refresh_token'], $token['expires_in']);
        } catch (Exception $exception) {
            $refreshTokenException = IntegrationException::refreshTokenException($exception);
            Rollbar::error($refreshTokenException, [
                'userId' => $user->getId(),
                'integrationType' => Integration::TYPE_GOOGLE_SERVICE
            ]);

            throw $refreshTokenException;
        }
    }
}
