<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use IncentiveFactory\Domain\Path\Course as DomainCourse;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer\CourseTransformer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course as EntityCourse;

/**
 * @template-extends ServiceEntityRepository<EntityCourse>
 */
final class CourseRepository extends ServiceEntityRepository implements CourseGateway
{
    public function __construct(ManagerRegistry $registry, private CourseTransformer $courseTransformer)
    {
        parent::__construct($registry, EntityCourse::class);
    }

    public function getCourseBySlug(string $slug): ?DomainCourse
    {
        /** @var ?EntityCourse $courseEntity */
        $courseEntity = $this->createQueryBuilder('c')
            ->addSelect('t')
            ->join('c.training', 't')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $courseEntity) {
            return null;
        }

        return $this->courseTransformer->transform($courseEntity);
    }

    public function countCoursesByTraining(Training $training): int
    {
        // TODO: Implement countCoursesByTraining() method.
        return 0;
    }

    public function getCoursesByTraining(Training $training): array
    {
        /** @var array<array-key, EntityCourse> $courseEntities */
        $courseEntities = $this->createQueryBuilder('c')
            ->addSelect('t')
            ->join('c.training', 't')
            ->where('t.id = :id')
            ->setParameter('id', $training->id()->toBinary())
            ->orderBy('c.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map([$this->courseTransformer, 'transform'], $courseEntities);
    }
}
