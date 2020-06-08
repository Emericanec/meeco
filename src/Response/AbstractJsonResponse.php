<?php

declare(strict_types=1);


namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractJsonResponse implements JsonResponseInterface
{
    public function toResponse(): JsonResponse
    {
        return JsonResponse::create($this->toArray());
    }
}
