<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Continent;
use App\Adventure\Gateway\ContinentGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

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
    public function getContinentsByWorld(string $id): array
    {
        return $this->findBy(['world' => $id]);
    }

    public function getContinentById(string $id): Continent
    {
        $continent = $this->find($id);

        if (null === $continent) {
            throw new InvalidArgumentException(sprintf('Continent %s is not found.', $id));
        }

        return $continent;
    }
}
