<?php

declare(strict_types=1);

namespace App\Content\Doctrine\Repository;

use App\Adventure\Entity\Player;
use App\Content\Entity\Quiz;
use App\Content\Entity\Quiz\Session;
use App\Content\Gateway\SessionGateway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T
 *
 * @template-extends ServiceEntityRepository<Session>
 * @template-implements SessionGateway<Session>
 */
final class SessionRepository extends ServiceEntityRepository implements SessionGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function hasFinished(Player $player, Quiz $quiz): bool
    {
        /** @var int $count */
        $count = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.player = :player')
            ->andWhere('p.quiz = :quiz')
            ->andWhere('p.finishedAt IS NOT NULL')
            ->setParameters([
                'player' => $player,
                'quiz' => $quiz,
            ])
            ->getQuery()
            ->getSingleScalarResult();

        return 1 === $count;
    }

    public function start(Session $session): void
    {
        $this->_em->persist($session);
        $this->_em->flush();
    }

    public function finish(Session $getSession): void
    {
        $this->_em->flush();
    }
}
