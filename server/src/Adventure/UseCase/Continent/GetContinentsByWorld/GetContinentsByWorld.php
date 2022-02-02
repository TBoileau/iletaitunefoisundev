<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Continent\GetContinentsByWorld;

use App\Adventure\Entity\World;
use App\Core\Bus\Query\QueryInterface;
use Symfony\Component\Validator\Constraints\NotNull;

final class GetContinentsByWorld implements QueryInterface
{
    #[NotNull]
    private World $world;

    public function __construct(World $world)
    {
        $this->world = $world;
    }

    public function getWorld(): World
    {
        return $this->world;
    }
}
