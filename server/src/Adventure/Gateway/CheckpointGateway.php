<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Journey;
use App\Adventure\Entity\Quest;

/**
 * @template T
 */
interface CheckpointGateway
{
    public function save(Checkpoint $checkpoint): void;

    public function hasAlreadySavedCheckpoint(Journey $journey, Quest $quest): bool;
}
