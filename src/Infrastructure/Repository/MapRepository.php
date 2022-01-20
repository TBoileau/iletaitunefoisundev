<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Adventure\Entity\Map;
use App\Domain\Adventure\Gateway\MapGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Map>
 * @template-implements MapGateway<Map>
 */
final class MapRepository extends ServiceEntityRepository implements MapGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Map::class);
    }
}
