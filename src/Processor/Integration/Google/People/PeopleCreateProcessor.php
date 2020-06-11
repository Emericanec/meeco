<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google\People;

use App\DTO\Integration\Google\PeopleCreateDTO;
use App\Entity\Integration;
use App\Processor\Integration\Google\AbstractGoogleProcessor;
use App\Processor\Integration\Google\GoogleRefreshTokenProcessor;
use App\Service\Google\Client;
use Google_Client;
use Google_Service_PeopleService;
use Google_Service_PeopleService_EmailAddress;
use Google_Service_PeopleService_Name;
use Google_Service_PeopleService_Person;
use Google_Service_PeopleService_PhoneNumber;

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

    public function process(Integration $integration, PeopleCreateDTO $dto): Google_Service_PeopleService_Person
    {
        $service = new Google_Service_PeopleService($this->getFreshGoogleClient($integration));

        $person = new Google_Service_PeopleService_Person();

        $name = new Google_Service_PeopleService_Name();
        $name->setDisplayName($dto->getName());
        $person->setNames($name);

        if (null !== $dto->getEmail()) {
            $email = new Google_Service_PeopleService_EmailAddress();
            $email->setValue($dto->getEmail());
            $person->setEmailAddresses($email);
        }

        if (null !== $dto->getPhone()) {
            $phone = new Google_Service_PeopleService_PhoneNumber();
            $phone->setValue($dto->getPhone());
            $person->setPhoneNumbers($phone);
        }

        return $service->people->createContact($person);
    }
}
