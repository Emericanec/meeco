<?php

declare(strict_types=1);

namespace App\Request\Api\V1\GooglePeople;

use App\Request\Api\AbstractGoogleServiceApiRequest;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class PeopleCreateRequest extends AbstractGoogleServiceApiRequest
{
    private ?string $name;

    private ?string $email;

    private ?string $phone;

    public function __construct(RequestStack $requestStack, ManagerRegistry $managerRegistry)
    {
        parent::__construct($requestStack, $managerRegistry);

        $this->name = $this->getString('name');
        $this->email = $this->getString('email');
        $this->phone = $this->getString('phone');
    }

    public function validate(): bool
    {
        if (!parent::validate()) {
            return false;
        }

        if (null === $this->name) {
            $this->setError('Name is required');

            return false;
        }

        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
