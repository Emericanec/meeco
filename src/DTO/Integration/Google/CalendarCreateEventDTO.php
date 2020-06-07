<?php

declare(strict_types=1);

namespace App\DTO\Integration\Google;

use DateTime;
use Google_Service_Calendar_EventDateTime;

class CalendarCreateEventDTO
{
    private string $summary;

    private DateTime $start;

    private ?DateTime $end = null;

    private ?string $location = null;

    private ?string $description = null;

    public function __construct(string $summary, DateTime $start)
    {
        $this->summary = $summary;
        $this->start = $start;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getStart(): Google_Service_Calendar_EventDateTime
    {
        $model = new Google_Service_Calendar_EventDateTime();
        $model->setDateTime($this->start);
        return $model;
    }

    public function setStart(DateTime $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): Google_Service_Calendar_EventDateTime
    {
        if (null === $this->end) {
            $model = $this->getStart();
        } else {
            $model = new Google_Service_Calendar_EventDateTime();
            $model->setDateTime($this->end);
        }
        return $model;
    }

    public function setEnd(?DateTime $end): void
    {
        $this->end = $end;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
