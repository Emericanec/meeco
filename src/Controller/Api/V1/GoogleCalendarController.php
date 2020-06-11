<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\DTO\Integration\Google\CalendarCreateEventDTO;
use App\Processor\Integration\Google\Calendar\CalendarCreateEventProcessor;
use App\Processor\Integration\Google\Calendar\CalendarGetListProcessor;
use App\Request\Api\V1\GoogleCalendar\CalendarListRequest;
use App\Request\Api\V1\GoogleCalendar\EventCreateRequest;
use App\Response\Api\GoogleCalendar\CalendarListResponse;
use App\Response\Api\GoogleCalendar\EventCreateResponse;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleCalendarController extends AbstractController
{
    /**
     * @Route("/api/v1/google_calendar/calendar_list", name="api_v1_google_calendar_calendar_list")
     * @param CalendarListRequest $request
     * @param CalendarGetListProcessor $processor
     * @return Response
     */
    public function calendarList(CalendarListRequest $request, CalendarGetListProcessor $processor): Response
    {
        if (!$request->validate()) {
            throw new InvalidArgumentException($request->getError());
        }

        $list = $processor->process($request->getIntegration());

        return (new CalendarListResponse($list))->toResponse();
    }

    /**
     * @Route("/api/v1/google_calendar/event_create", name="api_v1_google_calendar_event_create")
     * @param EventCreateRequest $request
     * @param CalendarCreateEventProcessor $processor
     * @return Response
     */
    public function eventCreate(EventCreateRequest $request, CalendarCreateEventProcessor $processor): Response
    {
        if (!$request->validate()) {
            throw new InvalidArgumentException($request->getError());
        }

        $dto = new CalendarCreateEventDTO($request->getCalendarId(), $request->getSummary(), $request->getStartDate());
        $dto->setDescription($request->getDescription());
        $dto->setEnd($request->getEndDate());
        $dto->setLocation($request->getLocation());

        $event = $processor->process($request->getIntegration(), $dto);

        return (new EventCreateResponse($event))->toResponse();
    }
}
