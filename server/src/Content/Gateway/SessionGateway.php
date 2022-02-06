<?php

declare(strict_types=1);

namespace App\Content\Gateway;

use App\Adventure\Entity\Player;
use App\Content\Entity\Quiz;
use App\Content\Entity\Quiz\Session;

/**
 * @template T
 */
interface SessionGateway
{
    public function hasFinished(Player $player, Quiz $quiz): bool;

    public function start(Session $session): void;

    public function finish(Session $getSession): void;
}
