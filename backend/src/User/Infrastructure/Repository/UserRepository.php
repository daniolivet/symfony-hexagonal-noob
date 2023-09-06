<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserDoesNotExist;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use App\User\Domain\Repository\IUserRepository;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, IUserRepository {
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct( ManagerRegistry $registry ) {
        parent::__construct( $registry, User::class );
    }

    /**
     * @param User $entity
     * @param bool $flush
     */
    public function save( User $entity, bool $flush = false ): void{
        $this->getEntityManager()->persist( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User $entity
     * @param bool $flush
     */
    public function remove( User $entity, bool $flush = false ): void{
        $this->getEntityManager()->remove( $entity );

        if ( $flush ) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword( PasswordAuthenticatedUserInterface $user, string $newHashedPassword ): void {
        if ( !$user instanceof User ) {
            throw new UnsupportedUserException( sprintf( 'Instances of "%s" are not supported.', $user::class ) );
        }

        $user->setPassword( $newHashedPassword );

        $this->save( $user, true );
    }

    /**
     * 
     * Find an user by email
     * 
     * @param string $email
     * @return User|null
     */
    public function findByEmail( string $email ): ?User {

        $criteria = Criteria::create();

        $criteria->where(
            Criteria::expr()->eq( 'email', $email )
        );
        $criteria->setMaxResults( 1 );

        $query = $this->getEntityManager()->createQueryBuilder();
        $query
            ->select( 'u' )
            ->from( User::class, 'u' )
            ->addCriteria( $criteria );

        $queryResponse = $query->getQuery();
        $user          = $queryResponse->getOneOrNullResult();

        return $user;
    }

    /**
     * Find an user by Uuid
     *
     * @param string $uuid
     * @return User|null
     */
    public function findByUuid( string $uuid ): ?User {
        $criteria = Criteria::create();

        $criteria->where(
            Criteria::expr()->eq( 'uuid', $uuid )
        );
        $criteria->setMaxResults( 1 );

        $query = $this->getEntityManager()->createQueryBuilder();
        $query
            ->select( 'u' )
            ->from( User::class, 'u' )
            ->addCriteria( $criteria );

        $queryResponse = $query->getQuery();
        return $queryResponse->getOneOrNullResult();
    }

    /**
     * Check if exists an user
     *
     * @param string $uuid
     * @return bool
     */
    public function exists( string $uuid ): bool {
        $user = $this->findByUuid($uuid);

        return null !== $user;
    }

}
