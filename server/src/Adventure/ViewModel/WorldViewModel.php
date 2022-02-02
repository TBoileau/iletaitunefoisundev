<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\World;

final class WorldViewModel
{
    private function __construct(public string $id, public string $name)
    {
    }

    public static function createFromWorld(World $world): WorldViewModel
    {
        return new self((string) $world->getId(), $world->getName());
    }
}
