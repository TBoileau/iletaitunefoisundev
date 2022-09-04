<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer;

use IncentiveFactory\Domain\Path\Course as DomainCourse;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Course as EntityCourse;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\TrainingRepository;

/**
 * @template-implements EntityTransformer<DomainCourse, EntityCourse>
 */
final class CourseTransformer implements EntityTransformer
{
    public function __construct(
        private TrainingTransformer $trainingTransformer,
        private TrainingRepository $trainingRepository,
    ) {
    }

    /**
     * @param EntityCourse $entity
     */
    public function transform($entity): DomainCourse
    {
        return DomainCourse::create(
            $entity->getId(),
            $entity->getPublishedAt(),
            $entity->getName(),
            $entity->getExcerpt(),
            $entity->getContent(),
            $entity->getSlug(),
            $entity->getImage(),
            $entity->getVideo(),
            $entity->getThread(),
            $entity->getLevel(),
            $this->trainingTransformer->transform($entity->getTraining())
        );
    }

    /**
     * @param DomainCourse  $entity
     * @param ?EntityCourse $target
     */
    public function reverseTransform($entity, $target = null): EntityCourse
    {
        if (null === $target) {
            $target = new EntityCourse();
        }

        /** @var Training $trainingEntity */
        $trainingEntity = $this->trainingRepository->find($entity->training()->id());

        return $target
            ->setId($entity->id())
            ->setPublishedAt($entity->publishedAt())
            ->setName($entity->name())
            ->setExcerpt($entity->excerpt())
            ->setContent($entity->content())
            ->setSlug($entity->slug())
            ->setImage($entity->image())
            ->setVideo($entity->video())
            ->setThread($entity->thread())
            ->setLevel($entity->level())
            ->setTraining($trainingEntity);
    }
}
