<?php

declare(strict_types=1);

namespace App\Controller\Admin\Integration;

use App\Controller\Admin\AbstractAdminController;
use App\Entity\User;
use App\Processor\Integration\Google\CalendarGetListProcessor;
use App\Processor\Integration\Google\CalendarRefreshTokenProcessor;
use App\Processor\Integration\Google\CalendarSaveTokenProcessor;
use App\Request\Integration\Google\CodeRequest;
use App\Service\Google\Client;
use Exception;
use Rollbar\Rollbar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class GoogleController extends AbstractAdminController
{
    /**
     * @Route("/admin/integration/google/oauth", name="admin_integration_google_oauth")
     * @param Client $client
     * @return Response
     */
    public function oauth(Client $client): Response
    {
        $url = $client->getClient()->createAuthUrl();
        return $this->render('admin/integration/google/oauth.html.twig', [
            'url' => $url
        ]);
    }

    /**
     * @Route("/admin/integration/google/oauth/code", name="admin_integration_google_oauth_code")
     * @param Client $client
     * @param CodeRequest $request
     * @param CalendarSaveTokenProcessor $processor
     * @return Response
     */
    public function code(Client $client, CodeRequest $request, CalendarSaveTokenProcessor $processor): Response
    {
        if (!$request->validate()) {
            return $this->redirectToRoute('admin_integration_google_oauth_invalid_code');
        }

        /** @var User $user */
        $user = $this->getUser();

        try {
            $token = $client->getClient()->fetchAccessTokenWithAuthCode($request->getCode());
            $processor->process($user, $token['access_token'], $token['refresh_token'], $token['expires_in']);
        } catch (Exception $e) {
            Rollbar::error($e, ['userId' => $user->getId(), 'code' => $request->getCode()]);

            return $this->redirectToRoute('admin_integration_google_oauth_invalid_code');
        }

        return $this->redirectToRoute('admin_integration_google_oauth');
    }

    /**
     * @Route("/admin/integration/google/oauth/inalid_code", name="admin_integration_google_oauth_invalid_code")
     * @return Response
     */
    public function invalidCode(): Response
    {
        return $this->render('admin/integration/google/invalid_code.html.twig');
    }
}
