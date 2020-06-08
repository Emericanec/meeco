<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use App\Service\Google\Client;
use Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_CalendarList;

class CalendarGetListProcessor
{
    private Client $client;

    private CalendarRefreshTokenProcessor $refreshTokenProcessor;

    public function __construct(Client $client, CalendarRefreshTokenProcessor $refreshTokenProcessor)
    {
        $this->client = $client;
        $this->refreshTokenProcessor = $refreshTokenProcessor;
    }

    /**
     * @param Integration $integration
     * @return Google_Service_Calendar_CalendarList
     * @throws Exception
     */
    public function process(Integration $integration): Google_Service_Calendar_CalendarList
    {
        $integration = $this->refreshTokenProcessor->refresh($integration->getUser());

        $this->client->getClient()->setAccessToken($integration->getAccessToken());
        $service = new Google_Service_Calendar($this->client->getClient());

        return $service->calendarList->listCalendarList();
    }
}
