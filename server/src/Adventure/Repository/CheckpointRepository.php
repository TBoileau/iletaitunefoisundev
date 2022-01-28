<?php

declare(strict_types=1);

namespace App\Adventure\Repository;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Checkpoint>
 * @template-implements CheckpointGateway<Checkpoint>
 */
final class CheckpointRepository extends ServiceEntityRepository implements CheckpointGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Checkpoint::class);
    }
}
