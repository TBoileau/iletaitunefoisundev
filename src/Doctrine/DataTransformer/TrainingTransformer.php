<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer;

use IncentiveFactory\Domain\Path\Training as DomainTraining;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training as EntityTraining;

/**
 * @template-implements EntityTransformer<DomainTraining, EntityTraining>
 */
final class TrainingTransformer implements EntityTransformer
{
    /**
     * @param EntityTraining $entity
     */
    public function transform($entity): DomainTraining
    {
        return DomainTraining::create(
            $entity->getId(),
            $entity->getPublishedAt(),
            $entity->getSlug(),
            $entity->getName(),
            $entity->getDescription(),
            $entity->getLevel(),
            $entity->getPrerequisites(),
            $entity->getSkills(),
            $entity->getImage()
        );
    }

    /**
     * @param DomainTraining  $entity
     * @param ?EntityTraining $target
     */
    public function reverseTransform($entity, $target = null): EntityTraining
    {
        if (null === $target) {
            $target = new EntityTraining();
        }

        return $target
            ->setId($entity->id())
            ->setPublishedAt($entity->publishedAt())
            ->setSlug($entity->slug())
            ->setName($entity->name())
            ->setDescription($entity->description())
            ->setLevel($entity->level())
            ->setPrerequisites($entity->prerequisites())
            ->setSkills($entity->skills())
            ->setImage($entity->image());
    }
}
