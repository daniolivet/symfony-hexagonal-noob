<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface IUserRepository {

    public function save(User $entity, bool $flush = false): void;

    public function remove(User $entity, bool $flush = false): void;

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;

}
