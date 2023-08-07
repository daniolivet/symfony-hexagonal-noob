<?php

namespace App\User\Application;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\User\Application\Events\UserCreatedEvent;

final class SendWelcomeEmailUseCase {

    public function __construct(
        private readonly MailerInterface $mailer
    )
    {}

    public function __invoke( UserCreatedEvent $event )
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject("Welcome to Symfony Hexagonal Noob App!!")
            ->html("<p>Welcome user {$event->getEmail()}</p>");

        $this->mailer->send($email);
    }

}
