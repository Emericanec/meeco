<?php

declare(strict_types=1);

namespace App\Generator\Action\GoogleCalendar;

use App\Generator\Action\AbstractAction;

class EventCreateAction extends AbstractAction
{
    public const PATH = '/api/v1/google_calendar/event_create';

    public function getTitle(): string
    {
        return 'Create event';
    }

    public function getName(): string
    {
        return 'google_calendar_event_create';
    }

    public function getDescription(): string
    {
        return 'Create new event in Google Calendar';
    }

    public function getForms(): array
    {
        return [
            [
                'title' => 'Calendar ID',
                'name' => 'calendarId',
                'type' => self::FORM_TYPE_NUMBER,
            ],
            [
                'title' => 'Summary',
                'name' => 'summary',
                'type' => self::FORM_TYPE_STRING,
            ],
            [
                'title' => 'Description',
                'name' => 'description',
                'type' => self::FORM_TYPE_STRING,
            ],
            [
                'title' => 'Location',
                'name' => 'location',
                'type' => self::FORM_TYPE_STRING,
            ],
            [
                'title' => 'Start Date',
                'name' => 'start',
                'type' => self::FORM_TYPE_STRING,
            ],
            [
                'title' => 'End Date',
                'name' => 'end',
                'type' => self::FORM_TYPE_STRING,
            ],
        ];
    }

    public function getRequests(): array
    {
        return [
            [
                'url' => self::BASE_URL . self::PATH,
                'method' => self::METHOD_POST,
                'headers' => ['Content-Type: application/json'],
                'payload' => [
                    'calendarId' => '[[calendarId]]',
                    'summary' => '[[summary]]',
                    'description' => '[[description]]',
                    'location' => '[[location]]',
                    'start' => '[[start]]',
                    'end' => '[[end]]',
                ],
                'mapping' => [
                    [
                        'title' => 'Event ID',
                        'name' => 'eventId',
                        'path' => '$.eventId',
                        'type' => self::FORM_TYPE_STRING,
                    ]
                ],
            ],
        ];
    }
}
