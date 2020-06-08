<?php

declare(strict_types=1);

namespace App\Processor\Security;

use App\Entity\User;
use App\Processor\Email\AfterRegistrationEmailProcessor;
use Doctrine\Persistence\ObjectManager;
use Rollbar\Rollbar;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Throwable;

class RegistrationProcessor
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
        $model->setRoles([User::ROLE_USER]);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        try {
            $this->emailProcessor->send($model->getEmail(), [
                'testo' => 'it is works'
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
