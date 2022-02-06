<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Repository;

use App\Content\Entity\Question;
use App\Content\Gateway\QuestionGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Question>
 * @template-implements QuestionGateway<Question>
 */
final class QuestionRepository extends ServiceEntityRepository implements QuestionGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }
}
