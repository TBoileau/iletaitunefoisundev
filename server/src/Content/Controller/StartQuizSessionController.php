<?php

declare(strict_types=1);

namespace App\Content\Controller;

use App\Adventure\Entity\Player;
use App\Content\Entity\Quiz;
use App\Content\UseCase\StartQuizSession\StartQuizSessionInput;
use App\Security\Entity\User;
use Symfony\Component\Security\Core\Security;

final class StartQuizSessionController
{
    public function __construct(private Security $security)
    {
    }

    public function __invoke(Quiz $quiz, StartQuizSessionInput $respondQuiz): StartQuizSessionInput
    {
        /** @var User $user */
        $user = $this->security->getUser();

        /** @var Player $player */
        $player = $user->getPlayer();

        $respondQuiz->quiz = $quiz;
        $respondQuiz->player = $player;

        return $respondQuiz;
    }
}
