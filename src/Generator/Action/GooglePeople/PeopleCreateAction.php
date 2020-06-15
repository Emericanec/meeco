<?php

declare(strict_types=1);

namespace App\Generator\Action\GooglePeople;

use App\Generator\Action\AbstractAction;

class PeopleCreateAction extends AbstractAction
{
    public const PATH = '/api/v1/google_people/people_create';

    public function getTitle(): string
    {
        return 'Create contact';
    }

    public function getName(): string
    {
        return 'google_people_people_create';
    }

    public function getDescription(): string
    {
        return 'Create new contact in Google Contacts';
    }

    public function getForms(): array
    {
        return [
            [
                'title' => 'Name',
                'name' => 'name',
                'type' => self::FORM_TYPE_STRING,
            ],
            [
                'title' => 'Email',
                'name' => 'email',
                'type' => self::FORM_TYPE_STRING,
            ],
            [
                'title' => 'Phone Number',
                'name' => 'phone',
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
                    'name' => '[[name]]',
                    'email' => '[[email]]',
                    'phone' => '[[phone]]',
                ],
                'mapping' => [
                    [
                        'title' => 'Contact ID',
                        'name' => 'id',
                        'path' => '$.id',
                        'type' => self::FORM_TYPE_STRING,
                    ]
                ],
            ],
        ];
    }
}
