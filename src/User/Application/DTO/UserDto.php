<?php

namespace App\User\Application\DTO;

use App\User\Application\DTO\ValueObjects\Email;
use App\User\Application\DTO\ValueObjects\Name;
use App\User\Application\DTO\ValueObjects\Password;
use App\User\Application\DTO\ValueObjects\Surnames;

final class UserDto {

    /**
     * @param string $uuid
     * @param Password $password
     * @param Email $email
     * @param Name $name
     * @param Surnames $surnames
     */
    public function __construct(
        private string $uuid,
        private Password $password,
        private Email $email,
        private Name $name,
        private Surnames $surnames
    ) {}
}
