<?php

namespace App\User\Domain\Events;

use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event {
    protected string $uuid;
    protected DateTimeImmutable $ocurredOn;

    /**
     * @param string $uuid
     */
    public function __construct( string $uuid ) {
        $this->uuid      = $uuid;
        $this->ocurredOn = new DateTimeImmutable();
    }

    /**
     * Get user identification
     * 
     * @return string
     */
    public function getUuid(): string {
        return $this->uuid;
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
