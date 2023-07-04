<?php

namespace App\User\Domain\EventsListener;

use App\User\Domain\Events\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserCreatedEventListener implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            UserCreatedEvent::NAME_EVENT => 'sendEmailEvent'
        ];
    }

    public function sendEmailAction( UserCreatedEvent $event ) { 
        return;
    }
    
}

