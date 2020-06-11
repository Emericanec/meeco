<?php

declare(strict_types=1);

namespace App\Request\Api\V1\GoogleCalendar;

use App\Request\Api\AbstractGoogleServiceApiRequest;
use DateTime;
use Exception;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class EventCreateRequest extends AbstractGoogleServiceApiRequest
{
    private ?int $calendarId;

    private ?string $summary;

    private ?string $description;

    private ?string $location;

    private ?string $start;

    private ?string $end;

    private ?DateTime $startDate = null;

    private ?DateTime $endDate = null;

    public function __construct(RequestStack $requestStack, ManagerRegistry $managerRegistry)
    {
        parent::__construct($requestStack, $managerRegistry);

        $this->calendarId = $this->getInteger('calendarId');
        $this->summary = $this->getString('summary');
        $this->description = $this->getString('description');
        $this->location = $this->getString('location');
        $this->start = $this->getString('start');
        $this->end = $this->getString('end');
    }

    public function validate(): bool
    {
        if (!parent::validate()) {
            return false;
        }

        if (null === $this->calendarId) {
            $this->setError('Calendar ID is required');

            return false;
        }

        if (null === $this->summary) {
            $this->setError('Summary is required');

            return false;
        }

        if (null === $this->start) {
            $this->setError('Start is required');

            return false;
        }

        try {
            $this->startDate = new DateTime($this->start);
        } catch (Exception $exception) {
            $this->setError('Start is not valid date');

            return false;
        }

        if (null !== $this->end) {
            try {
                $this->endDate = new DateTime($this->end);
            } catch (Exception $exception) {
                $this->setError('End is not valid date');

                return false;
            }
        }

        return true;
    }

    public function getCalendarId(): int
    {
        return $this->calendarId;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }
}
