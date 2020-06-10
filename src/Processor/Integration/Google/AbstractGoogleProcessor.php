<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use Google_Client;

abstract class AbstractGoogleProcessor
{
    abstract public function getGoogleClient(): Google_Client;

    abstract public function getRefreshTokenProcessor(): GoogleRefreshTokenProcessor;

    public function getFreshGoogleClient(Integration $integration): Google_Client
    {
        $this->getGoogleClient()->setAccessToken($integration->getAccessToken());
        if ($this->getGoogleClient()->isAccessTokenExpired()) {
            $integration = $this->getRefreshTokenProcessor()->refresh($integration->getUser());
            $this->getGoogleClient()->setAccessToken($integration->getAccessToken());
        }

        return $this->getGoogleClient();
    }
}
