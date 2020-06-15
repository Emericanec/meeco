<?php

declare(strict_types=1);

namespace App\Controller\ManyChat\Generator;

use App\Generator\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationGeneratorController extends AbstractController
{
    /**
     * @Route("/manychat/generator/generate", name="manychat_generator_generate")
     * @return Response
     */
    public function generate(): Response
    {
        $applicationData = (new Application())->getApplication();

        return $this->json($applicationData);
    }
}
