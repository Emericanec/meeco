<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Integration\GoogleServiceIntegration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class SettingsController extends AbstractAdminController
{
    /**
     * @Route("/admin/settings/general", name="admin_settings")
     */
    public function general(): Response
    {
        return $this->render('admin/settings/general.html.twig', [
            'user' => $this->getCurrentUser()
        ]);
    }

    /**
     * @Route("/admin/settings/integrations", name="admin_settings_integrations")
     * @param GoogleServiceIntegration $googleServiceIntegration
     * @return Response
     */
    public function integrations(GoogleServiceIntegration $googleServiceIntegration): Response
    {
        return $this->render('admin/settings/integrations.html.twig', [
            'googleServiceIntegration' => $googleServiceIntegration
        ]);
    }
}
