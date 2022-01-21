<?php

declare(strict_types=1);

namespace App\Infrastructure\Uuid;

use App\Domain\Shared\Uuid\UlidGeneratorInterface;
use Symfony\Component\Uid\Ulid;

final class UlidGenerator implements UlidGeneratorInterface
{
    public function generate(): Ulid
    {
        return new Ulid();
    }
}
