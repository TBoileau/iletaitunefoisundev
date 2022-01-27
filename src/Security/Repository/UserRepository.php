<?php

declare(strict_types=1);

namespace App\Security\Repository;

use App\Adventure\Entity\Player;
use App\Security\Entity\User;
use App\Security\Gateway\UserGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<User>
 * @template-implements UserGateway<User>
 */
final class UserRepository extends ServiceEntityRepository implements UserLoaderInterface, UserGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->findOneBy(['email' => $identifier]);
    }

    public function register(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function isUniqueEmail(string $email): bool
    {
        return 0 === $this->count(['email' => $email]);
    }

    public function createQueryBuilderUsersWhoHaveNotCreatedTheirPlayer(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u');

        $queryBuilder->where(
            $queryBuilder->expr()->notIn(
                'u',
                $this->_em->createQueryBuilder()
                    ->select('u2')
                    ->from(Player::class, 'p2')
                    ->join('p2.user', 'u2')
                    ->getDQL()
            )
        );

        return $queryBuilder;
    }
}
