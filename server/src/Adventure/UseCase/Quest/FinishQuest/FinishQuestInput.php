<?php

declare(strict_types=1);

namespace App\Adventure\UseCase\Quest\FinishQuest;

use App\Adventure\Entity\Player;
use App\Adventure\Entity\Quest;
use App\Adventure\UseCase\Quest\CheckpointInput;

final class FinishQuestInput extends CheckpointInput
{
    public static function create(Player $player, Quest $quest): FinishQuestInput
    {
        return new self($player, $quest);
    }
}
