<?php

declare(strict_types=1);

namespace App\Adventure\Repository;

use App\Adventure\Entity\Save;
use App\Adventure\Gateway\SaveGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Save>
 * @template-implements SaveGateway<Save>
 */
final class SaveRepository extends ServiceEntityRepository implements SaveGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Save::class);
    }
}
