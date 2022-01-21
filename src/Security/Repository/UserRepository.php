<?php

declare(strict_types=1);

namespace App\Security\Repository;

use App\Security\Entity\User;
use App\Security\Gateway\UserGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
