<?php

namespace App\User\Domain\Entity\ValueObjects;

final class Surnames {

    private string $value;

    /**
     * @param string $surnames
     */
    public function __construct( string $surnames ) {
        $this->value = $surnames;
    }

    /**
     * Get value of value object.
     * 
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

}
