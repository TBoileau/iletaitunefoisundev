<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Continent;
use App\Adventure\Entity\World;
use App\Adventure\Gateway\ContinentGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Continent>
 * @template-implements ContinentGateway<Continent>
 */
final class ContinentRepository extends ServiceEntityRepository implements ContinentGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Continent::class);
    }

    /**
     * @return array<array-key, Continent>
     */
    public function getContinentsByWorld(World $world): array
    {
        return $this->findBy(['world' => $world]);
    }
}
