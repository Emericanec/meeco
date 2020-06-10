<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use App\Service\Google\Client;
use Google_Client;
use Google_Service_People_ListConnectionsResponse;

class PeopleGetListProcessor extends AbstractGoogleProcessor
{
    private Client $client;

    private GoogleRefreshTokenProcessor $refreshTokenProcessor;

    public function __construct(Client $client, GoogleRefreshTokenProcessor $refreshTokenProcessor)
    {
        $this->client = $client;
        $this->refreshTokenProcessor = $refreshTokenProcessor;
    }

    public function getGoogleClient(): Google_Client
    {
        return $this->client->getClient();
    }

    public function getRefreshTokenProcessor(): GoogleRefreshTokenProcessor
    {
        return $this->refreshTokenProcessor;
    }

    public function process(Integration $integration): Google_Service_People_ListConnectionsResponse
    {
        $service = new \Google_Service_People($this->getFreshGoogleClient($integration));

        $optParams = array(
            'personFields' => 'names,emailAddresses',
        );

        return $service->people_connections->listPeopleConnections('people/me', $optParams);
    }
}
