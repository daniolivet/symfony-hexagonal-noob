<?php

namespace App\User\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class UserValidator {

    #[Assert\NotBlank]
    public string $password;

    #[Assert\Email(
        message:'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $surnames;

    /**
     * @param string $password
     * @param string $email
     * @param string $name
     * @param string $surnames
     */
    public function __construct(
        string $password,
        string $email,
        string $name,
        string $surnames
    ) {
        $this->password = $password;
        $this->email    = $email;
        $this->name     = $name;
        $this->surnames = $surnames;
    }

}
