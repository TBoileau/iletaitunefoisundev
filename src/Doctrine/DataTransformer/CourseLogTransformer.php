<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer;

use IncentiveFactory\Domain\Path\CourseLog as DomainCourseLog;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\CourseLog as EntityCourseLog;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\CourseRepository;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\PathRepository;

/**
 * @template-implements EntityTransformer<DomainCourseLog, EntityCourseLog>
 */
final class CourseLogTransformer implements EntityTransformer
{
    public function __construct(
        private CourseTransformer $courseTransformer,
        private CourseRepository $courseRepository,
        private PathTransformer $pathTransformer,
        private PathRepository $pathRepository
    ) {
    }

    /**
     * @param EntityCourseLog $entity
     */
    public function transform($entity): DomainCourseLog
    {
        return DomainCourseLog::create(
            $entity->getId(),
            $this->pathTransformer->transform($entity->getPath()),
            $this->courseTransformer->transform($entity->getCourse()),
            $entity->getBeganAt(),
            $entity->getCompletedAt()
        );
    }

    /**
     * @param DomainCourseLog  $entity
     * @param ?EntityCourseLog $target
     */
    public function reverseTransform($entity, $target = null): EntityCourseLog
    {
        if (null === $target) {
            $target = new EntityCourseLog();
        }

        /** @var Path $pathEntity */
        $pathEntity = $this->pathRepository->find($entity->path()->id());

        /** @var Course $courseEntity */
        $courseEntity = $this->courseRepository->find($entity->course()->id());

        return $target
            ->setId($entity->id())
            ->setCourse($courseEntity)
            ->setPath($pathEntity)
            ->setCompletedAt($entity->completedAt())
            ->setBeganAt($entity->beganAt());
    }
}
