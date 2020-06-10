<?php

declare(strict_types=1);

namespace App\Service\Google;

use Google_Client;
use Google_Exception;
use Google_Service_Calendar;
use Google_Service_People;

class Client
{
    private const REDIRECT_URL = 'https://meeco.app/admin/integration/google/oauth/code';

    private const ACCESS_TYPE = 'offline';

    private const PROMPT = 'consent';

    private Google_Client $client;

    /**
     * Client constructor.
     * @throws Google_Exception
     */
    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../config/google/client_id.json');
        $client->addScope([
            Google_Service_Calendar::CALENDAR,
            Google_Service_People::CONTACTS,
        ]);
        $client->setRedirectUri(self::REDIRECT_URL);
        $client->setAccessType(self::ACCESS_TYPE);
        $client->setPrompt(self::PROMPT);
        $client->setIncludeGrantedScopes(true);
        $this->client = $client;
    }

    public function getClient(): Google_Client
    {
        return $this->client;
    }
}
