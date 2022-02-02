<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Region;
use App\Adventure\Gateway\RegionGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Region>
 * @template-implements RegionGateway<Region>
 */
final class RegionRepository extends ServiceEntityRepository implements RegionGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function getRegionsByContinent(string $id): array
    {
        return $this->findBy(['continent' => $id]);
    }

    public function getRegionById(string $id): Region
    {
        $region = $this->find($id);

        if (null === $region) {
            throw new InvalidArgumentException(sprintf('Region %s is not found.', $id));
        }

        return $region;
    }
}
