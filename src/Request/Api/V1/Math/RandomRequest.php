<?php

declare(strict_types=1);

namespace App\Request\Api\V1\Math;

use App\Request\Api\AbstractApiRequest;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class RandomRequest extends AbstractApiRequest
{
    private int $min;
    private int $max;

    public function __construct(RequestStack $requestStack, ManagerRegistry $managerRegistry)
    {
        parent::__construct($requestStack, $managerRegistry);
        $this->min = (int)$this->getInteger('min', 1);
        $this->max = (int)$this->getInteger('max', 100);
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }
}
