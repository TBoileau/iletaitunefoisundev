<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\World;
use JsonSerializable;

final class WorldsViewModel implements JsonSerializable
{
    /**
     * @param array<array-key, WorldViewModel> $worlds
     */
    private function __construct(public array $worlds)
    {
    }

    /**
     * @param array<array-key, World> $worlds
     */
    public static function createFromWorlds(array $worlds): WorldsViewModel
    {
        return new self(array_map([WorldViewModel::class, 'createFromWorld'], $worlds));
    }

    /**
     * @return array<array-key, WorldViewModel>
     */
    public function jsonSerialize(): array
    {
        return $this->worlds;
    }
}
