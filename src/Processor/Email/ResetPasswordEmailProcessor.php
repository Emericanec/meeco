<?php

declare(strict_types=1);

namespace App\Processor\Email;

use App\Entity\User;
use Rollbar\Rollbar;
use Symfony\Component\Mailer\MailerInterface;
use Throwable;

class ResetPasswordEmailProcessor extends AbstractEmailProcessor
{
    public function __construct(MailerInterface $mailer)
    {
        parent::__construct($mailer);
        $this->prepare('Reset Password', 'email/reset_password.html.twig');
    }

    public function process(User $user): void
    {
        try {
            // @todo generate some link
            $this->send($user->getEmail(), ['resetPasswordLink' => 'https://meeco.app']);
        } catch (Throwable $exception) {
            Rollbar::error('reset password email send', [
                'error_message' => $exception->getMessage(),
                'error_trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
