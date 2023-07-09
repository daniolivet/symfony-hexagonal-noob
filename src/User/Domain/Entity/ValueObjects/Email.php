<?php

namespace App\User\Domain\Entity\ValueObjects;

use App\User\Domain\Repository\IUserRepository;
use App\User\Domain\Exception\UserExists;

final class Email {

    private string $value;

    /**
     * @param string $email
     */
    public function __construct( 
        string $email, 
        private readonly IUserRepository $repository 
    ) {
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
     * Ensure if an user already exists
     *
     * @param string $email
     * @throws UserExists
     * @return void
     */
    private function ensureIfExists( string $email ): void {
        $exist = $this->repository->findByEmail($email);
        
        if( null !== $exist ) {
            throw new UserExists("The user with email {$email} already exists.");
        }
    }

}
