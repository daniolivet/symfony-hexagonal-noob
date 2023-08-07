<?php

namespace App\User\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/* Domain */
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\ValueObjects\Email;
use App\User\Domain\Entity\ValueObjects\Name;
use App\User\Domain\Repository\IUserRepository;

/* Value Objects */
use App\User\Domain\Entity\ValueObjects\Password;
use App\User\Domain\Entity\ValueObjects\Surnames;
use App\User\Domain\Entity\ValueObjects\Uuid;

final class CreateUserUseCase {

    /**
     *
     * @param IUserRepository $repository
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        private readonly IUserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @param  Request $request
     * @return array
     */
    public function __invoke( array $requestData ): array{
        try {

            $user = $this->createUser($requestData);

            $this->hashUserPassword( $user );

            $this->repository->save( $user, true );

            $this->pullEvent( $user );

            return [
                'response' => true,
                'code'     => Response::HTTP_OK,
                'message'  => 'User created succesfully!',
            ];
        }catch ( \RuntimeException $e ) {
            return [
                'response' => false,
                'code'     => Response::HTTP_BAD_REQUEST,
                'message'  => $e->getMessage(),
            ];
        } catch( \Exception $e ) {
            return [
                'response' => false,
                'code'     => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'  => $e->getMessage(),
            ];
        }

    }

    /**
     * Create User Object
     *
     * @param array $data
     * @return User
     */
    private function createUser( array $data ): User {
        return User::create(
            Uuid::generate(),
            new Password( $data['password'] ),
            new Email(
                $data['email'],
                $this->repository
            ),
            new Name( $data['name'] ),
            new Surnames( $data['surnames'] )
        );
    }

    /**
     * Hash user password
     *
     * @param  User   $user
     * @return void
     */
    private function hashUserPassword( User $user ) {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword( $hashedPassword );
    }

    /**
     * Dispatch event
     *
     * @param  User   $user
     * @return void
     */
    private function pullEvent( User $user ) {
        foreach ( $user->pullDomainEvents() as $event ) {
            $this->eventDispatcher->dispatch( $event, $event::NAME_EVENT );
        }
    }

}
