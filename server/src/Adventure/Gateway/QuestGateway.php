<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Quest;

/**
 * @template T
 */
interface QuestGateway
{
    /**
     * @return array<array-key, Quest>
     */
    public function getQuestsByRegion(string $id): array;

    public function getQuestById(string $id): Quest;
}
