<?php

declare(strict_types=1);

namespace App\Security\Factory;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

class UuidV6Factory
{
    public function create(): UuidV6
    {
        return Uuid::v6();
    }
}
