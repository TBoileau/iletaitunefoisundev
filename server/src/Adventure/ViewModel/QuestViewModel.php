<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\Quest;

final class QuestViewModel
{
    private function __construct(
        public string $id,
        public string $name,
        public string $difficulty
    ) {
    }

    public static function createFromQuest(Quest $quest): QuestViewModel
    {
        return new self((string) $quest->getId(), $quest->getName(), $quest->getDifficulty()->name);
    }
}
