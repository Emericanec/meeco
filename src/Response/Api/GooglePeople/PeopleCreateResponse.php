<?php

declare(strict_types=1);

namespace App\Response\Api\GooglePeople;

use App\Response\AbstractJsonResponse;
use Google_Service_PeopleService_Person;

class PeopleCreateResponse extends AbstractJsonResponse
{
    private Google_Service_PeopleService_Person $person;

    public function __construct(Google_Service_PeopleService_Person $person)
    {
        $this->person = $person;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->person->getEtag()
        ];
    }
}
