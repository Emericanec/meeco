<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\DTO\Integration\Google\PeopleCreateDTO;
use App\Processor\Integration\Google\People\PeopleCreateProcessor;
use App\Processor\Integration\Google\People\PeopleGetListProcessor;
use App\Request\Api\V1\GooglePeople\PeopleCreateRequest;
use App\Request\Api\V1\GooglePeople\PeopleListRequest;
use App\Response\Api\GooglePeople\PeopleCreateResponse;
use App\Response\Api\GooglePeople\PeopleListResponse;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GooglePeopleController extends AbstractController
{
    /**
     * @Route("/api/v1/google_people/people_list", name="api_v1_google_people_people_list")
     * @param PeopleListRequest $request
     * @param PeopleGetListProcessor $processor
     * @return Response
     */
    public function peopleList(PeopleListRequest $request, PeopleGetListProcessor $processor): Response
    {
        if (!$request->validate()) {
            throw new InvalidArgumentException($request->getError());
        }

        $list = $processor->process($request->getIntegration());

        return (new PeopleListResponse($list))->toResponse();
    }

    /**
     * @Route("/api/v1/google_people/people_create", name="api_v1_google_people_people_create")
     * @param PeopleCreateRequest $request
     * @param PeopleCreateProcessor $processor
     * @return Response
     */
    public function peopleCreate(PeopleCreateRequest $request, PeopleCreateProcessor $processor): Response
    {
        if (!$request->validate()) {
            throw new InvalidArgumentException($request->getError());
        }

        $dto = new PeopleCreateDTO($request->getName(), $request->getEmail(), $request->getPhone());

        $person = $processor->process($request->getIntegration(), $dto);

        return (new PeopleCreateResponse($person))->toResponse();
    }
}
