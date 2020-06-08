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

class CalendarRefreshTokenProcessor
{
    private Client $client;

    private IntegrationRepository $repository;

    private CalendarSaveTokenProcessor $processor;

    public function __construct(Client $client, IntegrationRepository $repository, CalendarSaveTokenProcessor $processor)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->processor = $processor;
    }

    /**
     * @param User $user
     * @return Integration
     * @throws Exception
     */
    public function refresh(User $user): Integration
    {
        $integration = $this->repository->findOneByType($user, Integration::TYPE_GOOGLE_CALENDAR);
        if (null === $integration) {
            $exception = IntegrationException::notFoundException();

            Rollbar::error($exception, [
                'userId' => $user->getId(),
                'integrationType' => Integration::TYPE_GOOGLE_CALENDAR
            ]);

            throw $exception;
        }

        $token = $this->client->getClient()->refreshToken($integration->getRefreshToken());
        return $this->processor->process($user,  $token['access_token'], $token['refresh_token'], $token['expires_in']);
    }
}
