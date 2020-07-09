<?php

declare(strict_types=1);

namespace App\Processor\Security;

use App\Entity\User;
use App\Processor\Email\AfterRegistrationEmailProcessor;
use Doctrine\Persistence\ObjectManager;
use Rollbar\Rollbar;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Throwable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationProcessor extends AbstractController
{
    private ObjectManager $entityManager;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    private AfterRegistrationEmailProcessor $emailProcessor;

    public function __construct(
        ObjectManager $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder,
        AfterRegistrationEmailProcessor $emailProcessor
    ) {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->emailProcessor = $emailProcessor;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public function process(string $email, string $password): User
    {
        $model = new User();
        $model->setEmail($email);
        $model->setPassword($this->userPasswordEncoder->encodePassword($model, $password));
        $model->generateNewApiToken();
        $model->setPersonalHash($model->getEmail());
        $model->setRoles([User::ROLE_USER]);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        try {
            $confirmUri = $this->generateUrl('activate_account', [
                'userHash' => $model->getPersonalHash()
            ], UrlGeneratorInterface::ABSOLUTE_URL);
            $this->emailProcessor->send($model->getEmail(), [
                'confirmUri'=> $confirmUri
            ]);
        } catch (Throwable $exception) {
            Rollbar::error('after registration email send', [
                'error_message' => $exception->getMessage(),
                'error_trace' => $exception->getTraceAsString(),
            ]);
        }

        return $model;
    }
}
