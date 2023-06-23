<?php

namespace App\User\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/* Domain */
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\IUserRepository;

/* DTO */
use App\User\Application\DTO\UserDto;

/* Value Objects */
use App\User\Application\DTO\ValueObjects\Uuid;
use App\User\Application\DTO\ValueObjects\Email;
use App\User\Application\DTO\ValueObjects\Name;
use App\User\Application\DTO\ValueObjects\Password;
use App\User\Application\DTO\ValueObjects\Surnames;

final class CreateUserUseCase {

    /**
     * @param UserPasswordHasherInterface $pwdHasher
     * @param IUserRepository             $repository
     * @param UuidFactory                 $uuid
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $pwdHasher,
        private readonly IUserRepository $repository,
        private readonly UuidFactory $uuid
    ) {}

    /**
     * @param  Request $request
     * @return array
     */
    public function __invoke( array $requestData ): array{
        try {

            $userDto = $this->createUserDTO( $requestData );

            $user = new User(
                $userDto->getUuid(),
                $userDto->getPassword(),
                $userDto->getEmail(),
                $userDto->getName(),
                $userDto->getSurnames()
            );

            $this->repository->save( $user, true );

            return [];
        } catch ( \Exception $e ) {
            return [
                'response' => false,
                'code'     => Response::HTTP_BAD_REQUEST,
                'message'  => $e->getMessage(),
            ];
        }

    }

    /**
     * Create an user DTO
     *
     * @param  array $requestData
     * @throws Exception
     * @return UserDto
     */
    private function createUserDTO( array $requestData ): UserDto {

        return UserDto::create(
            Uuid::generate(),
            new Password( $requestData['password'] ),
            new Email( 
                $requestData['email'],
                $this->repository 
            ),
            new Name( $requestData['name'] ),
            new Surnames( $requestData['surnames'] )
        );
    }

}
