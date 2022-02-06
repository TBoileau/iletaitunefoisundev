<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Repository;

use App\Content\Entity\PlayerQuiz;
use App\Content\Gateway\PlayerQuizGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<PlayerQuiz>
 * @template-implements PlayerQuizGateway<PlayerQuiz>
 */
final class PlayerQuizRepository extends ServiceEntityRepository implements PlayerQuizGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerQuiz::class);
    }
}
