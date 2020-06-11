<?php

declare(strict_types=1);

namespace App\Response\Api\GoogleCalendar;

use App\Response\AbstractJsonResponse;
use Google_Service_Calendar_CalendarList;

class CalendarListResponse extends AbstractJsonResponse
{
    private Google_Service_Calendar_CalendarList $list;

    public function __construct(Google_Service_Calendar_CalendarList $list)
    {
        $this->list = $list;
    }

    public function toArray(): array
    {
        $result = [];
        while ($calendar = $this->list->next()) {
            $result[] = [
                'id' => $this->list->getItems()->getId(),
                'summary' => $this->list->getItems()->getSummary(),
            ];
        }
        return $result;
    }
}
