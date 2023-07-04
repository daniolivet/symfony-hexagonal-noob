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
use App\User\Application\DTO\UserValidator;

/* Value Objects */
use App\User\Application\DTO\ValueObjects\Uuid;
use App\User\Application\DTO\ValueObjects\Email;
use App\User\Application\DTO\ValueObjects\Name;
use App\User\Application\DTO\ValueObjects\Password;
use App\User\Application\DTO\ValueObjects\Surnames;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class CreateUserUseCase {

    /**
     * @param UserPasswordHasherInterface $pwdHasher
     * @param IUserRepository             $repository
     * @param UuidFactory                 $uuid
     */
    public function __construct(
        private readonly IUserRepository $repository,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @param  Request $request
     * @return array
     */
    public function __invoke( array $requestData ): array{
        try {
            $errors = $this->validateRequest( $requestData );

            if ( count( $errors ) > 0 ) {
                return [
                    'response' => false,
                    'code'     => Response::HTTP_BAD_REQUEST,
                    'message'  => 'There is errors in the request.',
                    'errors'   => $errors,
                ];
            }

            $userDto = $this->createUserDTO( $requestData );

            $user = User::create(
                $userDto->getUuid(),
                $userDto->getPassword(),
                $userDto->getEmail(),
                $userDto->getName(),
                $userDto->getSurnames()
            );

            $this->hashUserPassword($user);

            $this->repository->save( $user, true );

            $this->pullEvent($user);

            return [
                'response' => true,
                'code'     => Response::HTTP_OK,
                'message'  => 'User created succesfully!',
            ];
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
     * @param  array       $requestData
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

    /**
     *
     * Validate request data and return errors if exists.
     *
     * @param  array   $data
     * @return array
     */
    private function validateRequest( array $data ): array{
        $requestErrors = [];

        $userValidator = new UserValidator(
            $data['password'] ?? '',
            $data['email'] ?? '',
            $data['name'] ?? '',
            $data['surnames'] ?? ''
        );

        $errors = $this->validator->validate( $userValidator );

        if ( count( $errors ) > 0 ) {
            foreach ( $errors as $error ) {
                $requestErrors[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $requestErrors;
    }

    /**
     * Hash user password
     *
     * @param User $user
     * @return void
     */
    private function hashUserPassword( User $user ) {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
    }

    /**
     * Dispatch event
     *
     * @param User $user
     * @return void
     */
    private function pullEvent( User $user ) {
        foreach( $user->pullDomainEvents() as $event ) {
            $this->eventDispatcher->dispatch($event, $event::NAME_EVENT);
        }
    }

}
