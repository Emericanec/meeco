<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google\Calendar;

use App\DTO\Integration\Google\CalendarCreateEventDTO;
use App\Entity\Integration;
use App\Processor\Integration\Google\AbstractGoogleProcessor;
use App\Processor\Integration\Google\GoogleRefreshTokenProcessor;
use App\Service\Google\Client;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class CalendarCreateEventProcessor extends AbstractGoogleProcessor
{
    private Client $client;

    private GoogleRefreshTokenProcessor $refreshTokenProcessor;

    /**
     * CalendarCreateEventProcessor constructor.
     * @param Client $client
     * @param GoogleRefreshTokenProcessor $refreshTokenProcessor
     */
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

    public function process(Integration $integration, CalendarCreateEventDTO $dto): Google_Service_Calendar_Event
    {
        $event = new Google_Service_Calendar_Event();
        $event->setSummary($dto->getSummary());
        $event->setDescription($dto->getDescription());
        $event->setLocation($dto->getLocation());
        $event->setStart($dto->getStart());
        $event->setEnd($dto->getEnd());

        $service = new Google_Service_Calendar($this->getFreshGoogleClient($integration));

        return $service->events->insert($dto->getCalendarId(), $event);
    }
}
