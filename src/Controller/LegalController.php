<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    /**
     * @Route("/privacy", name="legal_privacy")
     * @return Response
     */
    public function privacy(): Response
    {
        return $this->render('legal/privacy.html.twig');
    }
}
