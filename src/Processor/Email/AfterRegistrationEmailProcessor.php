<?php

declare(strict_types=1);

namespace App\Processor\Email;

use Symfony\Component\Mailer\MailerInterface;

class AfterRegistrationEmailProcessor extends AbstractEmailProcessor
{
    public function __construct(MailerInterface $mailer)
    {
        parent::__construct($mailer);
        $this->prepare('Thank you for registration!', 'email/registration.html.twig');
    }
}
