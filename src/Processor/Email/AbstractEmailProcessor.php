<?php

declare(strict_types=1);

namespace App\Processor\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

abstract class AbstractEmailProcessor
{
    public const FROM_EMAIL = 'noreply@meeco.app';

    protected TemplatedEmail $email;

    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->email = (new TemplatedEmail())->from(self::FROM_EMAIL);
    }

    public function prepare(string $subject, string $htmlTemplate): self
    {
        $this->email = $this->email->subject($subject)->htmlTemplate($htmlTemplate);

        return $this;
    }

    /**
     * @param string $email
     * @param array $context
     * @throws TransportExceptionInterface
     */
    public function send(string $email, array $context = []): void
    {
        $this->email = $this->email->to($email)->context($context);
        $this->mailer->send($this->email);
    }
}
