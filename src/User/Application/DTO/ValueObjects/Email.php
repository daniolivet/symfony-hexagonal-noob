<?php

namespace App\User\Application\DTO\ValueObjects;

use App\User\Domain\Repository\IUserRepository;
use App\User\Application\DTO\Exception\InvalidFormatEmail;
use App\User\Application\DTO\Exception\UserExists;

final class Email {

    private string $value;

    /**
     * @param string $email
     */
    public function __construct( 
        string $email, 
        private readonly IUserRepository $repository 
    ) {
        $this->ensureIsValid( $email );
        $this->ensureIfExists( $email );
        $this->value = $email;
    }

    /**
     * Get value of value object
     *
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * Validate email format. Return a exception if not is valid.
     *
     * @param string $password
     * @throws InvalidFormatEmail
     * @return void
     */
    private function ensureIsValid( string $email ): void {
        $regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";

        if ( !preg_match( $regex, $email ) ) {
            throw new InvalidFormatEmail(
                "The email {$email} does not have a valid format."
            );
        }
    }

    private function ensureIfExists( string $email ) {
        $exist = $this->repository->findByEmail($email);
        
        if( count($exist) !== 0 ) {
            throw new UserExists("The user with email {$email} already exists.");
        }
    }

}
