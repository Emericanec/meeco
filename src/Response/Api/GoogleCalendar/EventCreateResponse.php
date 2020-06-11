<?php

declare(strict_types=1);

namespace App\Response\Api\GoogleCalendar;

use App\Response\AbstractJsonResponse;
use Google_Service_Calendar_Event;

class EventCreateResponse extends AbstractJsonResponse
{
    private Google_Service_Calendar_Event $event;

    public function __construct(Google_Service_Calendar_Event $event)
    {
        $this->event = $event;
    }

    public function toArray(): array
    {
        return [
            'eventId' => $this->event->getId()
        ];
    }
}
