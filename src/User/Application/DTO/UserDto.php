<?php

namespace App\User\Application\DTO;

final class  UserDto {

    public function __construct(
        private string $uuid,
        private string $password,
        private string $email,
        private string $name,
        private string $surnames
    ){}
}
