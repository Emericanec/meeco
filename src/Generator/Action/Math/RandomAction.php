<?php

declare(strict_types=1);

namespace App\Generator\Action\Math;

use App\Generator\Action\AbstractAction;

class RandomAction extends AbstractAction
{
    public const PATH = '/api/v1/math/random';

    public function getTitle(): string
    {
        return 'Random number';
    }

    public function getName(): string
    {
        return 'random_number';
    }

    public function getDescription(): string
    {
        return 'Generate random number';
    }

    public function getForms(): array
    {
        return [
            [
                'title' => 'Min random number',
                'name' => 'min',
                'type' => self::FORM_TYPE_NUMBER,
            ],
            [
                'title' => 'Max random number',
                'name' => 'max',
                'type' => self::FORM_TYPE_NUMBER,
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
                    'min' => '[[min]]',
                    'max' => '[[max]]',
                ],
                'mapping' => [
                    [
                        'title' => 'Random number',
                        'name' => 'result',
                        'path' => '$.result',
                        'type' => self::FORM_TYPE_NUMBER,
                    ]
                ],
            ],
        ];
    }
}
