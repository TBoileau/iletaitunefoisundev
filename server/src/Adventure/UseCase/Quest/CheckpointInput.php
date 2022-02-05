<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest;

use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;

abstract class CheckpointInput
{
    protected function __construct(public Player $player, public Quest $quest)
    {
    }
}
