<?php

declare(strict_types=1);

namespace App\Content\Controller;

use App\Content\Entity\Quiz;
use App\Content\UseCase\RespondQuiz\RespondQuizOutput;
use Symfony\Component\Messenger\MessageBusInterface;

final class RespondQuizController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(Quiz $quiz): RespondQuizOutput
    {
        return $this->messageBus->dispatch();
    }
}
