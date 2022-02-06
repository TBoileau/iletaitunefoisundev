<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Quest>
 * @template-implements QuestGateway<Quest>
 */
final class QuestRepository extends ServiceEntityRepository implements QuestGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quest::class);
    }

    public function getQuestById(int $id): ?Quest
    {
        /** @var ?Quest $quest */
        $quest = $this->createBaseQueryBuilder()
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();

        return $quest;
    }

    private function createBaseQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->addSelect('c')
            ->addSelect('i')
            ->join('q.course', 'c')
            ->leftJoin('q.quiz', 'i');
    }
}
