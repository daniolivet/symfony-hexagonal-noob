<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

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
     * @return array
     */
    public function findByEmail( string $email ): array {

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
        return $queryResponse->getResult();
    }

}
