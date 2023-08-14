<?php

namespace App\User\Domain\Exception;

final class UserDoesNotExist extends \RuntimeException {

    public function __construct(string $email)
    {
        parent::__construct("User with email {$email} does not exist");
    }

}