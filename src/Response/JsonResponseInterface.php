<?php

declare(strict_types=1);

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

interface JsonResponseInterface
{
    public function toArray(): array;

    public function toResponse(): JsonResponse;
}
