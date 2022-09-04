<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use IncentiveFactory\Domain\Path\Path as DomainPath;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer\PathTransformer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path as EntityPath;

/**
 * @extends ServiceEntityRepository<EntityPath>
 */
final class PathRepository extends ServiceEntityRepository implements PathGateway
{
    public function __construct(ManagerRegistry $registry, private PathTransformer $pathTransformer)
    {
        parent::__construct($registry, EntityPath::class);
    }

    public function begin(DomainPath $path): void
    {
        $pathEntity = $this->pathTransformer->reverseTransform($path);

        $this->_em->persist($pathEntity);
        $this->_em->flush();
    }

    public function hasAlreadyBegun(PlayerInterface $player, Training $training): bool
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->join('p.player', 'player')
            ->join('p.training', 'training')
            ->where('player.id = :player_id')
            ->andWhere('training.id = :training_id')
            ->setParameter('player_id', $player->id()->toBinary())
            ->setParameter('training_id', $training->id()->toBinary());

        /** @var int $numberOfPaths */
        $numberOfPaths = $queryBuilder->getQuery()->getSingleScalarResult();

        return $numberOfPaths > 0;
    }

    public function getPathsByPlayer(PlayerInterface $player): array
    {
        /** @var array<array-key, EntityPath> $pathEntities */
        $pathEntities = $this->createQueryBuilder('p')
            ->addSelect('player')
            ->addSelect('training')
            ->join('p.player', 'player')
            ->join('p.training', 'training')
            ->where('player.id = :player_id')
            ->setParameter('player_id', $player->id()->toBinary())
            ->orderBy('p.beganAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map([$this->pathTransformer, 'transform'], $pathEntities);
    }

    public function complete(DomainPath $path): void
    {
        // TODO: Implement complete() method.
    }

    public function getPathById(string $id): ?DomainPath
    {
        // TODO: Implement getPathById() method.
        return null;
    }
}
