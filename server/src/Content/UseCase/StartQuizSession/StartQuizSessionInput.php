<?php

declare(strict_types=1);

namespace App\Content\UseCase\StartQuizSession;

use App\Adventure\Entity\Player;
use App\Content\Entity\Quiz;

final class StartQuizSessionInput
{
    public Quiz $quiz;

    public Player $player;
}
