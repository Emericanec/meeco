<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Request\Api\V1\Math\RandomRequest;
use App\Response\Api\Math\RandomIntegerResponse;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function random_int;

class MathController extends AbstractController
{
    /**
     * @Route("/api/v1/math/random", name="api_v1_math_random")
     * @param RandomRequest $request
     * @return Response
     * @throws Exception
     */
    public function random(RandomRequest $request): Response
    {
        $result = random_int($request->getMin(), $request->getMax());

        $response = new RandomIntegerResponse($result);

        return $response->toResponse();
    }
}
