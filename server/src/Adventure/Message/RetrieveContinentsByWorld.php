<?php

declare(strict_types=1);

namespace App\Adventure\Message;

use App\Adventure\Entity\World;
use App\Core\CQRS\MessageInterface;

final class RetrieveContinentsByWorld implements MessageInterface
{
    public function __construct(public World $world)
    {
    }
}
