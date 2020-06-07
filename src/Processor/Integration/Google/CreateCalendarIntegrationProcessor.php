<?php

declare(strict_types=1);

namespace App\Processor\Integration\Google;

use App\Entity\Integration;
use App\Entity\User;
use App\Repository\IntegrationRepository;
use DateInterval;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Exception;

class CreateCalendarIntegrationProcessor
{
    private ObjectManager $objectManager;

    private IntegrationRepository $repository;

    public function __construct(IntegrationRepository $repository, ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param string $token
     * @param int $expiresIn
     * @return Integration
     * @throws Exception
     */
    public function process(User $user, string $token, int $expiresIn): Integration
    {
        $now = new DateTime();
        $expiredAt = (new DateTime())->add(new DateInterval("PT{$expiresIn}S"));

        $model = $this->repository->findOneByType($user, Integration::TYPE_GOOGLE_CALENDAR);
        if (null === $model) {
            $model = new Integration();
            $model->setUser($user);
            $model->setType(Integration::TYPE_GOOGLE_CALENDAR);
            $model->setTokenType(Integration::TOKEN_TYPE_BEARER);
            $model->setCreatedAt($now);
        }

        $model->setAccessToken($token);
        $model->setUpdatedAt($now);
        $model->setExpiredAt($expiredAt);

        $this->objectManager->persist($model);
        $this->objectManager->flush();

        return $model;
    }
}
