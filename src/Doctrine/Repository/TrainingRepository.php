<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;

final class TrainingRepository implements TrainingGateway
{
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
        return [];
    }

    public function findOneBySlug(string $slug): ?Training
    {
        // TODO: Implement findOneBySlug() method.
        return null;
    }
}
