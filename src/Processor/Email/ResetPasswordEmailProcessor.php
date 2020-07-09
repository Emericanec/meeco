<?php

declare(strict_types=1);

namespace App\Processor\Email;

use App\Entity\User;
use Rollbar\Rollbar;
use Symfony\Component\Mailer\MailerInterface;
use Throwable;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class ResetPasswordEmailProcessor extends AbstractEmailProcessor
{
    private $router;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router )
    {
        parent::__construct($mailer);
        $this->prepare('Reset Password', 'email/reset_password.html.twig');
        $this->router = $router;
    }

    public function process(User $user): void
    {
        $uriToPasswordReset = $this->router->generate('enter_new_password', [
            'userId' => $user->getPersonalHash()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        try {
            // @todo generate some link
            $this->send($user->getEmail(), ['resetPasswordLink' => $uriToPasswordReset]);
        } catch (Throwable $exception) {
            Rollbar::error('reset password email send', [
                'error_message' => $exception->getMessage(),
                'error_trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
