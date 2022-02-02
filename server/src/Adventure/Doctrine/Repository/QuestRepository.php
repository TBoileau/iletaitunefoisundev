<?php

declare(strict_types=1);

namespace App\Adventure\Doctrine\Repository;

use App\Adventure\Entity\Quest;
use App\Adventure\Gateway\QuestGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

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

    public function getQuestsByRegion(string $id): array
    {
        return $this->findBy(['region' => $id]);
    }

    public function getRelativesByQuest(string $id): array
    {
        return $this->getQuestById($id)->getRelatives()->toArray();
    }

    public function getQuestById(string $id): Quest
    {
        $quest = $this->find($id);

        if (null === $quest) {
            throw new InvalidArgumentException(sprintf('Quest %s is not found.', $id));
        }

        return $quest;
    }
}
