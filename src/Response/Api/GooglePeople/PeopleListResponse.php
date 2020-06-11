<?php

declare(strict_types=1);

namespace App\Response\Api\GooglePeople;

use App\Response\AbstractJsonResponse;
use Google_Service_People_EmailAddress;
use Google_Service_People_ListConnectionsResponse;
use Google_Service_People_Name;
use Google_Service_People_Person;
use Google_Service_People_PhoneNumber;

class PeopleListResponse extends AbstractJsonResponse
{
    private Google_Service_People_ListConnectionsResponse $list;

    public function __construct(Google_Service_People_ListConnectionsResponse $list)
    {
        $this->list = $list;
    }

    public function toArray(): array
    {
        $result = [];
        while ($person = $this->list->next()) {
            if ($person instanceof Google_Service_People_Person) {
                $result[] = [
                    'id' => $person->getEtag(),
                    'name' => $this->getPersonName($person),
                    'email' => $this->getPersonEmail($person),
                    'phone' => $this->getPersonPhone($person),
                ];
            }
        }

        return $result;
    }

    private function getPersonEmail(Google_Service_People_Person $person): ?string
    {
        foreach ($person->getEmailAddresses() as $address) {
            if ($address instanceof Google_Service_People_EmailAddress) {
                return (string)$address->getValue();
            }
        }

        return null;
    }

    private function getPersonName(Google_Service_People_Person $person): ?string
    {
        foreach ($person->getNames() as $name) {
            if ($name instanceof Google_Service_People_Name) {
                return (string)$name->getDisplayName();
            }
        }

        return null;
    }

    private function getPersonPhone(Google_Service_People_Person $person): ?string
    {
        foreach ($person->getNames() as $phone) {
            if ($phone instanceof Google_Service_People_PhoneNumber) {
                return (string)$phone->getValue();
            }
        }

        return null;
    }
}
