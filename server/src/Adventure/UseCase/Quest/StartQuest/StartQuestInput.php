<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest\StartQuest;

use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\UseCase\Quest\CheckpointInput;

final class StartQuestInput extends CheckpointInput
{
    public static function create(Player $player, Quest $quest): StartQuestInput
    {
        return new self($player, $quest);
    }
}
