<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/admin/settings/general", name="admin_settings")
     */
    public function general(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('admin/settings/general.html.twig', [
            'user' => $user
        ]);
    }
}
