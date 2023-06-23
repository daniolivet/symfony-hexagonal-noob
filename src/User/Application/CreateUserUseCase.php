<?php

namespace App\User\Application;

use App\User\Application\DTO\UserDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Factory\UuidFactory;
use App\User\Domain\Repository\IUserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/* Value Objects */
use App\User\Application\DTO\ValueObjects\Uuid;
use App\User\Application\DTO\ValueObjects\Email;
use App\User\Application\DTO\ValueObjects\Name;
use App\User\Application\DTO\ValueObjects\Password;
use App\User\Application\DTO\ValueObjects\Surnames;

final class CreateUserUseCase {

    /**
     * @param UserPasswordHasherInterface $pwdHasher
     * @param IUserRepository $repository
     * @param UuidFactory $uuid
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $pwdHasher,
        private readonly IUserRepository $repository,
        private readonly UuidFactory $uuid
    ) {}

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke( Request $request ): array {
        try {
            $userData = json_decode( $request->getContent(), true );

            $userDto = new UserDto(
                Uuid::generate(),
                new Password( 'Malaga1997//' ),
                new Email('dani@gmail.com'),
                new Name('dani'),
                new Surnames('olivet')
            );

            return [];
        } catch ( \Exception $e ) {
            return [
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

    }

}
