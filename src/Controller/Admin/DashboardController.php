<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @IsGranted("ROLE_USER")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
