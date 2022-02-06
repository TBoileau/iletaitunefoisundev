<?php

declare(strict_types=1);

namespace App\Content\UseCase\RespondQuiz;

use App\Adventure\Entity\Player;
use App\Content\Entity\Quiz;

final class RespondQuizInput
{
    public Quiz $quiz;

    public Player $player;
}
