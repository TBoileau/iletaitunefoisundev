<?php

declare(strict_types=1);

namespace App\Admin\Repository;

use App\Admin\Entity\Administrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Administrator>
 */
final class AdministratorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrator::class);
    }
}
