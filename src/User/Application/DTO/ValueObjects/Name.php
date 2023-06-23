<?php

namespace App\User\Application\DTO\ValueObjects;

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
