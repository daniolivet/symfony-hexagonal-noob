<?php

namespace App\User\Domain\Entity\ValueObjects;

final class Name {

    private string $value;

    /**
     * @param string $name
     */
    public function __construct( string $name ) {
        $this->value = $name;
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
