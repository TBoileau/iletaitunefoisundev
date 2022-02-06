<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Quest;

/**
 * @template T
 */
interface QuestGateway
{
    public function getQuestById(int $id): ?Quest;
}
