<?php

declare(strict_types=1);

namespace App\Adventure\Repository;

use App\Adventure\Entity\Journey;
use App\Adventure\Gateway\JourneyGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Journey>
 * @template-implements JourneyGateway<Journey>
 */
final class JourneyRepository extends ServiceEntityRepository implements JourneyGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journey::class);
    }
}
