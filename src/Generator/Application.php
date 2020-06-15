<?php

declare(strict_types=1);

namespace App\Generator;

use App\Generator\Action\ActionInterface;
use App\Generator\Action\GoogleCalendar\EventCreateAction;
use App\Generator\Action\GooglePeople\PeopleCreateAction;
use App\Generator\Action\Math\RandomAction;

class Application implements ApplicationInterface
{
    public function getApplication(): array
    {
        return [
            'auth' => $this->getAuthData(),
            'actions' => $this->getActionsData(),
        ];
    }

    private function getActionsData(): array
    {
        $result = [];
        foreach ($this->getActions() as $action) {
            $result[] = $action->toArray();
        }
        return $result;
    }

    /**
     * @return ActionInterface[]
     */
    private function getActions(): array
    {
        return [
            new RandomAction(),
            new EventCreateAction(),
            new PeopleCreateAction(),
        ];
    }

    private function getAuthData(): array
    {
        return [
            'type' => 'APIKEY',
            'params' => [
                [
                    'name' => 'api_token',
                    'title' => 'Enter api_token:'
                ]
            ],
            'connection' => [
                'qs' => [
                    'api_token' => '[[api_token]]'
                ]
            ]
        ];
    }
}
