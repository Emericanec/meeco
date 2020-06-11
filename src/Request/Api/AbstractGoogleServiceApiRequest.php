<?php

declare(strict_types=1);

namespace App\Request\Api;

use App\Entity\Integration;
use App\Repository\IntegrationRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractGoogleServiceApiRequest extends AbstractApiRequest
{
    protected ManagerRegistry $managerRegistry;

    protected Integration $integration;

    public function __construct(RequestStack $requestStack, ManagerRegistry $managerRegistry)
    {
        parent::__construct($requestStack, $managerRegistry);
        $this->managerRegistry = $managerRegistry;
    }

    public function validate(): bool
    {
        $integrationRepository = $this->managerRegistry->getRepository(IntegrationRepository::class);
        if (!$integrationRepository instanceof IntegrationRepository) {
            $this->setError('IntegrationRepository not found');

            return false;
        }

        $integration = $integrationRepository->findOneByType($this->getUser(), Integration::TYPE_GOOGLE_SERVICE);
        if (null === $integration) {
            $this->setError('Integration not found');

            return false;
        }

        $this->integration = $integration;

        return parent::validate();
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }
}
