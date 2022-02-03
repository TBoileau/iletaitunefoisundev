<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Quest;
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

    public function save(Checkpoint $checkpoint): void
    {
        $this->_em->persist($checkpoint);
        $this->_em->flush();
    }

    public function hasAlreadySavedCheckpoint(Journey $journey, Quest $quest): bool
    {
        return $this->count(['journey' => $journey, 'quest' => $quest]) > 0;
    }
}
