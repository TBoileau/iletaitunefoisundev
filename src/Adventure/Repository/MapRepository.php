<?php

declare(strict_types=1);

namespace App\Adventure\Repository;

use App\Adventure\Entity\Map;
use App\Adventure\Gateway\MapGateway;
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
