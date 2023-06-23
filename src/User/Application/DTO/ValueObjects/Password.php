<?php 

namespace App\User\Application\DTO\ValueObjects;

use App\User\Application\DTO\Exception\InvalidFormatPassword;

final class Password {

    private string $value;

    public function __construct(string $password)
    {
        $this->ensureIsValid($password);
        $this->value = $password;
    }

    /**
     * Get value of value object.
     *
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * Validate password format. Return a exception if not is valid.
     *
     * @param string $password
     * @throws InvalidFormatPassword
     * @return void
     */
    private function ensureIsValid(string $password): void {
        $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9\s]).{8,}$/";

        if( !preg_match($regex, $password) ) {
            throw new InvalidFormatPassword();
        }
    }

}
