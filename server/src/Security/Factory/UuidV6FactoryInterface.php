<?php

declare(strict_types=1);

namespace App\Security\Factory;

use Symfony\Component\Uid\UuidV6;

interface UuidV6FactoryInterface
{
    public function create(): UuidV6;
}
