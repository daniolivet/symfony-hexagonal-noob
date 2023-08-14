<?php

namespace App\User\Application\Validate;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginUserValidator
{
    #[Assert\Email(
        message:'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank()]
    public string $email;

    #[Assert\NotBlank]
    public string $password;

    public function __construct( string $email, string $password )
    {
        $this->email    = $email;
        $this->password = $password;
    }
}