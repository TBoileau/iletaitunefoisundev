<?php

declare(strict_types=1);

namespace App\Domain\Shared\Uuid;

use Symfony\Component\Uid\Ulid;

interface UlidGeneratorInterface
{
    public function generate(): Ulid;
}
