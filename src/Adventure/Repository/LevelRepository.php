<?php

declare(strict_types=1);

namespace App\Adventure\Repository;

use App\Adventure\Entity\Level;
use App\Adventure\Gateway\LevelGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Level>
 * @template-implements LevelGateway<Level>
 */
final class LevelRepository extends ServiceEntityRepository implements LevelGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Level::class);
    }
}
