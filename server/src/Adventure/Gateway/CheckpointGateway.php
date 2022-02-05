<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Checkpoint;
use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;

/**
 * @template T
 */
interface CheckpointGateway
{
    public function save(Checkpoint $checkpoint): void;

    public function hasStartedQuest(Player $player, Quest $quest): bool;

    public function hasFinishedQuest(Player $player, Quest $quest): bool;

    public function getCheckpointByPlayerAndQuest(Player $player, Quest $quest): ?Checkpoint;
}
