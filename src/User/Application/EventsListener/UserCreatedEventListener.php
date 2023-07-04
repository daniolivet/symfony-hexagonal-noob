<?php

namespace App\User\Application\EventsListener;

use App\User\Application\SendWelcomeEmailUseCase;
use App\User\Application\Events\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserCreatedEventListener implements EventSubscriberInterface {

    public function __construct(
        private readonly SendWelcomeEmailUseCase $sendWelcomeEmailUseCase
    )
    {}

    public static function getSubscribedEvents() {
        return [
            UserCreatedEvent::NAME_EVENT => 'sendEmailAction'
        ];
    }

    public function sendEmailAction( UserCreatedEvent $event ) { 
        ($this->sendWelcomeEmailUseCase)($event);
    }
    
}

