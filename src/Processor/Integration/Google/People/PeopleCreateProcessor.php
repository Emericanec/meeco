<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google\People;

use App\Entity\Integration;
use App\Processor\Integration\Google\AbstractGoogleProcessor;
use App\Processor\Integration\Google\GoogleRefreshTokenProcessor;
use App\Service\Google\Client;
use Google_Client;
use Google_Service_PeopleService_Person;

class PeopleCreateProcessor extends AbstractGoogleProcessor
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

    public function process(Integration $integration): Google_Service_PeopleService_Person
    {
        $service = new \Google_Service_PeopleService($this->getFreshGoogleClient($integration));

        $person = new Google_Service_PeopleService_Person();

        return $service->people->createContact($person);
    }


}
