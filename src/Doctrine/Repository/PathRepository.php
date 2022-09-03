<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;

final class PathRepository implements PathGateway
{
    public function begin(Path $path): void
    {
        // TODO: Implement begin() method.
    }

    public function hasAlreadyBegan(PlayerInterface $player, Training $training): bool
    {
        // TODO: Implement hasAlreadyBegan() method.
        return false;
    }

    public function findByPlayer(PlayerInterface $player): array
    {
        // TODO: Implement findByPlayer() method.
        return [];
    }
}
