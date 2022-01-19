<?php

declare(strict_types=1);

namespace App\Domain\Shared\Uuid;

use Symfony\Component\Uid\UuidV4;

interface UuidGeneratorInterface
{
    public function generate(): UuidV4;
}
