<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use IncentiveFactory\Domain\Path\Training as DomainTraining;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer\TrainingTransformer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training as TrainingEntity;

/**
 * @template-extends ServiceEntityRepository<TrainingEntity>
 */
final class TrainingRepository extends ServiceEntityRepository implements TrainingGateway
{
    public function __construct(ManagerRegistry $registry, private TrainingTransformer $trainingTransformer)
    {
        parent::__construct($registry, TrainingEntity::class);
    }

    /**
     * @return array<array-key, DomainTraining>
     */
    public function getTrainings(): array
    {
        /** @var array<array-key, TrainingEntity> $trainingEntities */
        $trainingEntities = $this->createQueryBuilder('t')
            ->orderBy('t.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map([$this->trainingTransformer, 'transform'], $trainingEntities);
    }

    public function getTrainingBySlug(string $slug): ?DomainTraining
    {
        // TODO: Implement findOneBySlug() method.
        return null;
    }
}
