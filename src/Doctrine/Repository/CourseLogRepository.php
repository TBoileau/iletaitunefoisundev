<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseLog as DomainCourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer\CourseLogTransformer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\CourseLog as EntityCourseLog;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<EntityCourseLog>
 */
final class CourseLogRepository extends ServiceEntityRepository implements CourseLogGateway
{
    public function __construct(ManagerRegistry $registry, private CourseLogTransformer $courseLogTransformer)
    {
        parent::__construct($registry, EntityCourseLog::class);
    }

    public function begin(DomainCourseLog $courseLog): void
    {
        $courseLogEntity = $this->courseLogTransformer->reverseTransform($courseLog);

        $this->_em->persist($courseLogEntity);
        $this->_em->flush();
    }

    public function complete(DomainCourseLog $courseLog): void
    {
        $courseLogEntity = $this->find($courseLog->id());
        $this->courseLogTransformer->reverseTransform($courseLog, $courseLogEntity);
        $this->_em->flush();
    }

    public function hasAlreadyBegan(PlayerInterface $player, Course $course): bool
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->join('c.path', 'path')
            ->join('path.player', 'player')
            ->join('c.course', 'course')
            ->where('player.id = :player_id')
            ->andWhere('course.id = :course_id')
            ->setParameter('player_id', $player->id()->toBinary())
            ->setParameter('course_id', $course->id()->toBinary());

        /** @var int $numberOfCourses */
        $numberOfCourses = $queryBuilder->getQuery()->getSingleScalarResult();

        return $numberOfCourses > 0;
    }

    public function countCoursesCompletedByPath(Path $path): int
    {
        // TODO: Implement countCoursesCompletedByPath() method.
        return 0;
    }

    public function getCourseLogByPathAndCourse(Path $path, Course $course): ?DomainCourseLog
    {
        /** @var ?EntityCourseLog $courseLog */
        $courseLog = $this->createQueryBuilder('c')
            ->addSelect('path')
            ->addSelect('player')
            ->addSelect('course')
            ->addSelect('training')
            ->join('c.path', 'path')
            ->join('path.player', 'player')
            ->join('c.course', 'course')
            ->join('course.training', 'training')
            ->where('path.id = :path_id')
            ->andWhere('course.id = :course_id')
            ->setParameter('path_id', $path->id()->toBinary())
            ->setParameter('course_id', $course->id()->toBinary())
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $courseLog) {
            return null;
        }

        return $this->courseLogTransformer->transform($courseLog);
    }

    public function getCourseLogById(string $id): ?DomainCourseLog
    {
        /** @var ?EntityCourseLog $courseLogEntity */
        $courseLogEntity = $this->createQueryBuilder('c')
            ->addSelect('path')
            ->addSelect('player')
            ->addSelect('course')
            ->addSelect('training')
            ->join('c.path', 'path')
            ->join('path.player', 'player')
            ->join('c.course', 'course')
            ->join('course.training', 'training')
            ->where('c.id = :id')
            ->setParameter('id', Ulid::fromString($id)->toBinary())
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $courseLogEntity) {
            return null;
        }

        return $this->courseLogTransformer->transform($courseLogEntity);
    }
}
