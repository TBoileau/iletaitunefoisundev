<?php

declare(strict_types=1);

namespace App\Adventure\ViewModel;

use App\Adventure\Entity\Quest;
use JsonSerializable;

final class QuestsViewModel implements JsonSerializable
{
    /**
     * @param array<array-key, QuestViewModel> $quests
     */
    private function __construct(public array $quests)
    {
    }

    /**
     * @param array<array-key, Quest> $quests
     */
    public static function createFromQuests(array $quests): QuestsViewModel
    {
        return new self(array_map([QuestViewModel::class, 'createFromQuest'], $quests));
    }

    /**
     * @return array<array-key, QuestViewModel>
     */
    public function jsonSerialize(): array
    {
        return $this->quests;
    }
}
