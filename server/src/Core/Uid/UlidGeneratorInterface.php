<?php

declare(strict_types=1);

namespace App\Core\Uid;

use Symfony\Component\Uid\Ulid;

interface UlidGeneratorInterface
{
    public function generate(): Ulid;
}
