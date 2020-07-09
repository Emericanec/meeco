<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\Common\RequestValidationException;
use App\Processor\Email\ResetPasswordEmailProcessor;
use App\Processor\Security\RegistrationProcessor;
use App\Request\Security\EnterNewPasswordRequest;
use App\Request\Security\RegistrationRequest;
use App\Request\Security\ResetPasswordRequest;
use Doctrine\DBAL\Types\TextType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('admin_dashboard');
        }

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
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $error = null;
        if ($request->getRequest()->isMethod(Request::METHOD_POST)) {
            try {
                $request->validate();
                $registrationProcessor->process($request->getEmail(), $request->getPassword());

                return $this->redirectToRoute('app_registration_success');
            } catch (RequestValidationException $exception) {
                $error = $exception->getMessage();
            }
        }

        return $this->render('security/registration.html.twig', ['error' => $error]);
    }

    /**
     * @Route("/registration/activate_account/{userHash}", name="activate_account")
     */
    public function activateAccount(string $userHash): Response
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $em->getRepository(User::class)->findOneBy(['personalHash' => $userHash]);
        if (empty($currentUser)) {
            $message = 'Что-то пошло не так';
        } else {
            $currentUser->setAccountStatus(User::ACCOUNT_STATUS_CONFIRMED);
            $em->persist($currentUser);
            $em->flush();
            $message = 'Аккаунт активирован';
        }
        return $this->render('email/confirmed.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/registration/success", name="app_registration_success")
     */
    public function registrationSuccess(): Response
    {
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('security/registration_success.html.twig');
    }

    /**
     * @Route("/reset", name="app_reset_password")
     * @param ResetPasswordRequest $request
     * @param ResetPasswordEmailProcessor $processor
     * @return Response
     */
    public function resetPassword(ResetPasswordRequest $request, ResetPasswordEmailProcessor $processor): Response
    {
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $error = null;
        if ($request->getRequest()->isMethod(Request::METHOD_POST)) {
            try {
                $request->validate();
                $processor->process($request->getUser());
                return $this->redirectToRoute('app_reset_password_success');
            } catch (RequestValidationException $exception) {
                $error = $exception->getMessage();
            }
        }

        return $this->render('security/reset_password.html.twig', ['error' => $error]);
    }

    /**
     * @Route("/reset/success", name="app_reset_password_success")
     * @return Response
     */
    public function resetPasswordSuccess(): Response
    {
        if ($this->isGranted(User::ROLE_USER)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('security/reset_password_success.html.twig');
    }

    /**
     * @Route("/reset/resetPassword/{userId}", name="enter_new_password")
     */

    public function resetPasswordForm(string $userId): Response
    {
        $resetPassword = null;
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['personalHash' => $userId]);
        if (empty($user)) {
            return $this->render('security/error.html.twig');
        }
        if (!empty($_POST)) {
            $resetPassword = $_POST['newPassword'] === $_POST['repeatedNewPassword'];
        }
        if ($resetPassword) {
            try {
                $em = $this->getDoctrine()->getManager();
                $user->setPassword(md5($_POST['newPassword']));
                $em->persist($user);
                $em->flush();
            } catch (RequestValidationException $exception) {
                $error = $exception->getMessage();
                $this->render('security/reset_password_form.html.twig', [
                    'error' => $error,
                ]);
            }
            return $this->render('security/password_reseted.html.twig');
        }
        return $this->render('security/reset_password_form.html.twig', [
            'isPasswordsEqual' => $resetPassword
        ]);
    }

}
