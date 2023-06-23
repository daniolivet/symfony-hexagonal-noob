<?php

namespace App\User\Application\DTO;

use App\User\Application\DTO\ValueObjects\Email;
use App\User\Application\DTO\ValueObjects\Name;
use App\User\Application\DTO\ValueObjects\Password;
use App\User\Application\DTO\ValueObjects\Surnames;
use App\User\Application\DTO\ValueObjects\Uuid;

final class UserDto {

    /**
     * @param string   $uuid
     * @param Password $password
     * @param Email    $email
     * @param Name     $name
     * @param Surnames $surnames
     */
    public function __construct(
        private string $uuid,
        private Password $password,
        private Email $email,
        private Name $name,
        private Surnames $surnames
    ) {}

    /**
     * 
     * Uuid getter
     * 
     * @return string
     */
    public function getUuid(): string {
        return $this->uuid;
    }

    /**
     * 
     * Password getter
     * 
     * @return string
     */
    public function getPassword(): string {
        return $this->password->getValue();
    }

    /**
     * 
     * Email getter
     * 
     * @return mixed
     */
    public function getEmail(): string {
        return $this->email->getValue();
    }

    /**
     * 
     * Name getter
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name->getValue();
    }

    /**
     * 
     * Surnames getter
     * 
     * @return string
     */
    public function getSurnames(): string {
        return $this->surnames->getValue();
    }

    public static function create(
        string $uuid,
        Password $password,
        Email $email,
        Name $name,
        Surnames $surnames
    ) {
        return new self(
            $uuid,
            $password,
            $email,
            $name,
            $surnames
        );
    }
}
