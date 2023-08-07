<?php

namespace App\User\Application\Events;

use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event {

    public const NAME_EVENT = "user.created";

    protected string $email;
    protected DateTimeImmutable $ocurredOn;

    /**
     * @param string $uuid
     */
    public function __construct( string $email ) {
        $this->email     = $email;
        $this->ocurredOn = new DateTimeImmutable();
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Get date when event was created
     *
     * @return DateTimeImmutable
     */
    public function getOcurredOn(): DateTimeImmutable {
        return $this->ocurredOn;
    }

}
