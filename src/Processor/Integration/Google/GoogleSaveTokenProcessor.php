<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use App\Entity\User;
use App\Repository\IntegrationRepository;
use DateInterval;
use DateTime;
use Doctrine\Persistence\ObjectManager;

class GoogleSaveTokenProcessor
{
    private ObjectManager $objectManager;

    private IntegrationRepository $repository;

    public function __construct(IntegrationRepository $repository, ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->repository = $repository;
    }

    public function process(User $user, string $accessToken, string $refreshToken, int $expiresIn): Integration
    {
        $now = new DateTime();
        $expiredAt = (new DateTime())->add(new DateInterval("PT{$expiresIn}S"));

        $model = $this->repository->findOneByType($user, Integration::TYPE_GOOGLE_SERVICE);
        if (null === $model) {
            $model = new Integration();
            $model->setUser($user);
            $model->setType(Integration::TYPE_GOOGLE_SERVICE);
            $model->setTokenType(Integration::TOKEN_TYPE_BEARER);
            $model->setCreatedAt($now);
        }

        $model->setAccessToken($accessToken);
        $model->setRefreshToken($refreshToken);
        $model->setUpdatedAt($now);
        $model->setExpiredAt($expiredAt);

        $this->objectManager->persist($model);
        $this->objectManager->flush();

        return $model;
    }
}
