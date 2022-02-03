<?php

declare(strict_types=1);

namespace App\Adventure\Gateway;

use App\Adventure\Entity\Player;

/**
 * @template T
 */
interface PlayerGateway
{
    public function create(Player $player): void;
}
