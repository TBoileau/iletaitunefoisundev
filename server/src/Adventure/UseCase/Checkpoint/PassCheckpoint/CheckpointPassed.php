<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Checkpoint\PassCheckpoint;

use App\Adventure\Entity\Checkpoint;
use App\Core\Bus\Event\EventInterface;

final class CheckpointPassed implements EventInterface
{
    public function __construct(private Checkpoint $checkpoint)
    {
    }

    public function getCheckpoint(): Checkpoint
    {
        return $this->checkpoint;
    }
}
