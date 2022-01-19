<?php

declare(strict_types=1);

namespace App\Infrastructure\Uuid;

use App\Domain\Shared\Uuid\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): UuidV4
    {
        return Uuid::v4();
    }
}
