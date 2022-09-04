<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer;

use IncentiveFactory\Domain\Path\Path as DomainPath;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Path as EntityPath;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Training;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\PlayerRepository;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository\TrainingRepository;

/**
 * @template-implements EntityTransformer<DomainPath, EntityPath>
 */
final class PathTransformer implements EntityTransformer
{
    public function __construct(
        private PlayerTransformer $playerTransformer,
        private TrainingTransformer $trainingTransformer,
        private TrainingRepository $trainingRepository,
        private PlayerRepository $playerRepository
    ) {
    }

    /**
     * @param EntityPath $entity
     */
    public function transform($entity): DomainPath
    {
        return DomainPath::create(
            $entity->getId(),
            $this->playerTransformer->transform($entity->getPlayer()),
            $this->trainingTransformer->transform($entity->getTraining()),
            $entity->getBeganAt(),
            $entity->getCompletedAt(),
        );
    }

    /**
     * @param DomainPath  $entity
     * @param ?EntityPath $target
     */
    public function reverseTransform($entity, $target = null): EntityPath
    {
        if (null === $target) {
            $target = new EntityPath();
        }

        /** @var Player $playerEntity */
        $playerEntity = $this->playerRepository->find($entity->player()->id());

        /** @var Training $trainingEntity */
        $trainingEntity = $this->trainingRepository->find($entity->training()->id());

        return $target
            ->setId($entity->id())
            ->setPlayer($playerEntity)
            ->setTraining($trainingEntity)
            ->setBeganAt($entity->beganAt())
            ->setCompletedAt($entity->completedAt());
    }
}
