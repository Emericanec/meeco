<?php

declare(strict_types=1);

namespace App\Processor\Security;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationProcessor
{
    private ObjectManager $entityManager;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(ObjectManager $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
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
        $model->setRoles([User::ROLE_USER]);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }
}
