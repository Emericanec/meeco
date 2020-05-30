<?php

declare(strict_types=1);

namespace App\Controller;

use App\Processor\Security\RegistrationProcessor;
use App\Request\Security\RegistrationRequest;
use Exception;
use LogicException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/registration", name="app_registration")
     * @param RegistrationRequest $request
     * @param RegistrationProcessor $registrationProcessor
     * @return Response
     */
    public function registration(RegistrationRequest $request, RegistrationProcessor $registrationProcessor): Response
    {
        $error = null;
        if ($request->getRequest()->isMethod(Request::METHOD_POST)) {
            try {
                if (!$request->validate()) {
                    throw new RuntimeException($request->getError());
                }

                $registrationProcessor->process($request->getEmail(), $request->getPassword());

                return $this->redirectToRoute('app_registration_success');
            } catch (Exception $exception) {
                $error = $exception->getMessage();
            }
        }

        return $this->render('security/registration.html.twig', ['error' => $error]);
    }

    /**
     * @Route("/registration/success", name="app_registration_success")
     */
    public function registrationSuccess(): Response
    {
        return $this->render('security/registration_success.html.twig');
    }
}
