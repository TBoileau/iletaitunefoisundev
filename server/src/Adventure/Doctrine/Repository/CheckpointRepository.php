<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Gateway\CheckpointGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

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

    public function getCheckpointById(string $id): Checkpoint
    {
        $checkpoint = $this->find($id);

        if (null === $checkpoint) {
            throw new InvalidArgumentException(sprintf('Checkpoint %s is not found.', $id));
        }

        return $checkpoint;
    }
}
