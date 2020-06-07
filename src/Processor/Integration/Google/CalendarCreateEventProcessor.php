<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\DTO\Integration\Google\CalendarCreateEventDTO;
use App\Entity\Integration;
use App\Service\Google\Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class CalendarCreateEventProcessor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function process(Integration $integration, CalendarCreateEventDTO $dto): Google_Service_Calendar_Event
    {
        $event = new Google_Service_Calendar_Event();
        $event->setSummary($dto->getSummary());
        $event->setDescription($dto->getDescription());
        $event->setLocation($dto->getLocation());
        $event->setStart($dto->getStart());
        $event->setEnd($dto->getEnd());

        $client = $this->client->getClient();
        $client->setAccessToken($integration->getAccessToken());
        $service = new Google_Service_Calendar($client);

        return $service->events->insert('primary', $event);
    }
}
