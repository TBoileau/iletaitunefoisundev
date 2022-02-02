<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\World;
use App\Adventure\Gateway\WorldGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<World>
 * @template-implements WorldGateway<World>
 */
final class WorldRepository extends ServiceEntityRepository implements WorldGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, World::class);
    }

    public function getWorlds(): array
    {
        return $this->findAll();
    }

    public function getWorldById(string $id): World
    {
        $world = $this->find($id);

        if (null === $world) {
            throw new InvalidArgumentException(sprintf('World %s is not found.', $id));
        }

        return $world;
    }
}
