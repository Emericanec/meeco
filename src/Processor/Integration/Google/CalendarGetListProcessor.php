<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use App\Service\Google\Client;
use Google_Service_Calendar;
use Google_Service_Calendar_CalendarList;

class CalendarGetListProcessor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function process(Integration $integration): Google_Service_Calendar_CalendarList
    {
        $this->client->getClient()->setAccessToken($integration->getAccessToken());
        $service = new Google_Service_Calendar($this->client->getClient());

        return $service->calendarList->listCalendarList();
    }
}
