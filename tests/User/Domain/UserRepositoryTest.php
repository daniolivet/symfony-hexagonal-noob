<?php

namespace App\Tests\User\Domain;

use App\Tests\DoctrineTestCase;
use App\User\Application\DTO\ValueObjects\Uuid;
use App\User\Domain\Repository\UserRepository;
use App\User\Domain\Entity\User;

class UserRepositoryTest extends DoctrineTestCase {

    /**
     * @test
     */
    public function itShouldSaveAUser(): void{
        /**
         * @var UserRepository $repository
         */
        $repository = $this->getContainer()->get( UserRepository::class );
        $user       = $this->makeUser();

        $repository->save( $user, true );
        $this->clearUnitOfWork();

        $this->assertTrue( $repository->exists($user->getUuid()) );
    }

    /**
     * @test
     */
    public function itShouldFindAnUserByEmail(): void{
        $user       = $this->makeUser();
        $repository = $this->repositoryWithUser( $user );

        $foundUser = $repository->findByEmail( $user->getEmail() );

        $this->assertTrue( $this->equalsTo( $foundUser->getUuid(), $user->getUuid() ) );
        $this->assertTrue( $this->equalsTo( $foundUser->getEmail(), $user->getEmail() ) );
        $this->assertTrue( $this->equalsTo( $foundUser->getName(), $user->getName() ) );
        $this->assertTrue( $this->equalsTo( $foundUser->getSurnames(), $user->getSurnames() ) );
    }

    /**
     * Make a fake user for test respository
     *
     * @return User
     */
    private function makeUser(): User{

        $uuid     = Uuid::generate();
        $password = 'Dani1234//';
        $email    = 'dani@gmail.com';
        $name     = 'Dani';
        $surnames = 'Olivet Jimenez';

        return new User(
            $uuid, $password,
            $email, $name, $surnames
        );
    }

    /**
     * @param User $user
     * @return UserRepository
     */
    private function repositoryWithUser( User $user ): UserRepository {
        /**
         * @var UserRepository $repository
         */
        $repository = $this->getContainer()->get( UserRepository::class );

        $repository->save( $user, true );
        $this->clearUnitOfWork();

        return $repository;
    }

    /**
     * Check if two properties is equals to
     *
     * @param string $property_one
     * @param string $property_two
     * @return boolean
     */
    private function equalsTo(string $property_one, string $property_two ): bool {
        return $property_one === $property_two;
    }

}
